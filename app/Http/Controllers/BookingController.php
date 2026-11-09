<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethod;
use App\Http\Requests\Booking\CheckoutRequest;
use App\Http\Requests\Booking\UploadProofRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Show booking checkout page.
     */
    public function showCheckout(string $slug, Request $request): View|RedirectResponse
    {
        $court = Court::with('venue')
            ->where('slug', $slug)
            ->firstOrFail();

        $venue = $court->venue;
        $bookingDate = $request->query('date', Carbon::today()->toDateString());
        $slotsParam = $request->query('slots', '');

        $startTimes = array_filter(array_map('trim', explode(',', $slotsParam)));

        // If no slots selected, redirect to court detail
        if (empty($startTimes)) {
            return redirect()->route('lapangan.detail', $court->slug)
                ->with('warning', 'Silakan pilih minimal satu jam main terlebih dahulu.');
        }

        $now = Carbon::now();
        $primeStart = Carbon::createFromTimeString($venue->prime_start_time ?? '17:00:00');
        $primeEnd = Carbon::createFromTimeString($venue->prime_end_time ?? '23:00:00');

        $slotItems = [];
        foreach ($startTimes as $time) {
            $carbonTime = Carbon::createFromTimeString($time);
            $startFormatted = $carbonTime->format('H:i');
            $endFormatted = $carbonTime->copy()->addHour()->format('H:i');
            $isPrime = $carbonTime->gte($primeStart) && $carbonTime->lt($primeEnd);
            $price = $isPrime ? (int) $court->price_prime : (int) $court->price_regular;

            $slotItems[] = [
                'start_time' => $startFormatted,
                'end_time' => $endFormatted,
                'time_label' => "{$startFormatted} – {$endFormatted}",
                'is_prime' => $isPrime,
                'price' => $price,
            ];
        }

        usort($slotItems, fn ($a, $b) => strcmp($a['start_time'], $b['start_time']));

        $totalPrice = array_sum(array_column($slotItems, 'price'));

        return view('booking.checkout', compact('court', 'venue', 'bookingDate', 'slotItems', 'totalPrice', 'slug'));
    }

    /**
     * Handle checkout submit to create pending booking.
     */
    public function storeCheckout(string $slug, CheckoutRequest $request): RedirectResponse
    {
        $court = Court::with('venue')
            ->where('slug', $slug)
            ->firstOrFail();

        $booking = $this->bookingService->createPending(
            user: $request->user(),
            court: $court,
            bookingDate: $request->validated('booking_date'),
            startTimes: $request->validated('slots'),
            paymentMethod: PaymentMethod::from($request->validated('payment_method')),
            customerData: [
                'name' => $request->validated('customer_name'),
                'phone' => $request->validated('customer_phone'),
                'email' => $request->validated('customer_email'),
                'notes' => $request->validated('notes'),
            ],
        );

        return redirect()->route('booking.konfirmasi', ['code' => $booking->booking_code]);
    }

    /**
     * Show booking confirmation & payment page.
     */
    public function showKonfirmasi(Request $request): View
    {
        $code = $request->query('code');

        if ($code) {
            $booking = Booking::with(['items.court', 'venue', 'payment'])
                ->where('booking_code', $code)
                ->firstOrFail();
        } else {
            $booking = Booking::with(['items.court', 'venue', 'payment'])
                ->where('user_id', $request->user()->id)
                ->where('status', BookingStatus::Pending)
                ->latest()
                ->firstOrFail();
        }

        $this->authorize('view', $booking);

        $now = Carbon::now();
        $countdown = 0;
        if ($booking->status === BookingStatus::Pending && $booking->expires_at) {
            $countdown = max(0, $now->diffInSeconds($booking->expires_at, false));
        }

        return view('booking.konfirmasi', compact('booking', 'countdown'));
    }

    /**
     * Handle payment proof upload.
     */
    public function uploadProof(Booking $booking, UploadProofRequest $request): RedirectResponse
    {
        $this->authorize('update', $booking);

        $this->bookingService->uploadProof($booking, $request->file('proof'));

        return redirect()->route('booking.sukses', ['code' => $booking->booking_code])
            ->with('status', 'Bukti transfer berhasil diunggah dan sedang dalam verifikasi.');
    }

    /**
     * Show booking success / e-ticket page.
     */
    public function showSukses(Request $request): View
    {
        $code = $request->query('code');

        if ($code) {
            $booking = Booking::with(['items.court', 'venue', 'payment'])
                ->where('booking_code', $code)
                ->firstOrFail();
        } else {
            $booking = Booking::with(['items.court', 'venue', 'payment'])
                ->where('user_id', $request->user()->id)
                ->latest()
                ->firstOrFail();
        }

        $this->authorize('view', $booking);

        return view('booking.sukses', compact('booking'));
    }

    /**
     * Stream payment proof file to authorized users.
     */
    public function showProof(Booking $booking): StreamedResponse
    {
        $this->authorize('view', $booking);

        $payment = $booking->payment;
        $path = $payment?->proof_path;

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404, 'File bukti pembayaran tidak ditemukan.');
        }

        return Storage::disk('local')->response($path);
    }

    /**
     * Customer cancels their pending booking.
     */
    public function cancelPending(Booking $booking, Request $request): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $this->bookingService->cancelPendingByCustomer($booking, $request->user());

        return redirect()->route('user.dashboard')
            ->with('status', 'Booking berhasil dibatalkan.');
    }
}
