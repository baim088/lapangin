<?php

namespace App\Http\Controllers\Operator;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentStatus;
use App\Enums\SlotBlockType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\BlockSlotRequest;
use App\Http\Requests\Operator\CancelBookingRequest;
use App\Http\Requests\Operator\RejectBookingRequest;
use App\Http\Requests\Operator\StoreCourtRequest;
use App\Http\Requests\Operator\UpdateCourtRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use App\Services\ExpireStaleBookings;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OperatorController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected ExpireStaleBookings $expireStaleBookings,
    ) {}

    /**
     * Resolve the venue that belongs to the operator or fallback for super_admin.
     */
    protected function getOperatorVenue(User $user): Venue
    {
        if ($user->venue_id) {
            $venue = Venue::find($user->venue_id);
            if ($venue) {
                return $venue;
            }
        }

        $firstVenue = Venue::first();
        if (! $firstVenue) {
            abort(404, 'Venue belum terdaftar dalam sistem.');
        }

        return $firstVenue;
    }

    /**
     * Display operator dashboard with KPIs and today's incoming bookings.
     */
    public function dashboard(Request $request): View
    {
        $venue = $this->getOperatorVenue($request->user());
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // Trigger lazy cleaner for today's venue slots
        $this->expireStaleBookings->handle(null, $today);

        // KPI 1: Today's Bookings Count
        $todayBookingsCount = Booking::where('venue_id', $venue->id)
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->count();

        // KPI 2: Today's Revenue (confirmed or done)
        $todayRevenue = (int) Booking::where('venue_id', $venue->id)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::Done])
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->sum('total_amount');

        // KPI 3: Pending verification / confirmation queue
        $pendingVerificationCount = Booking::where('venue_id', $venue->id)
            ->where(function ($q) {
                $q->where('status', BookingStatus::Pending)
                    ->orWhereHas('payment', fn ($pq) => $pq->where('status', PaymentStatus::PendingVerification));
            })
            ->count();

        // KPI 4: Cancelled bookings today
        $todayCancelledCount = Booking::where('venue_id', $venue->id)
            ->whereIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->count();

        // Incoming bookings today (latest 10)
        $todayBookings = Booking::with(['items.court', 'user', 'payment'])
            ->where('venue_id', $venue->id)
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->latest()
            ->take(10)
            ->get();

        // Operating hours count for occupancy calculation
        $openHour = (int) substr($venue->open_time, 0, 2);
        $closeHour = (int) substr($venue->close_time, 0, 2);
        $totalOperatingHours = max(1, $closeHour - $openHour);

        // Occupancy per court today
        $courts = Court::where('venue_id', $venue->id)->get();
        $courtOccupancies = [];
        foreach ($courts as $court) {
            if ($court->status === CourtStatus::Maintenance) {
                $courtOccupancies[] = [
                    'court' => $court,
                    'rate' => 0,
                    'is_maintenance' => true,
                ];

                continue;
            }

            $bookedSlotsCount = CourtSlot::where('court_id', $court->id)
                ->whereDate('booking_date', $today)
                ->where(function ($q) use ($now) {
                    $q->whereNull('locked_until')
                        ->orWhere('locked_until', '>', $now);
                })
                ->count();

            $rate = min(100, (int) round(($bookedSlotsCount / $totalOperatingHours) * 100));
            $courtOccupancies[] = [
                'court' => $court,
                'rate' => $rate,
                'is_maintenance' => false,
            ];
        }

        // 7-day revenue summary
        $weeklyRevenue = [];
        $weeklyTotal = 0;
        for ($i = 6; $i >= 0; $i--) {
            $dayDate = Carbon::today()->subDays($i)->toDateString();
            $dayRevenue = (int) Booking::where('venue_id', $venue->id)
                ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::Done])
                ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $dayDate))
                ->sum('total_amount');

            $weeklyRevenue[] = [
                'date' => $dayDate,
                'label' => Carbon::parse($dayDate)->isoFormat('dd'),
                'amount' => $dayRevenue,
            ];
            $weeklyTotal += $dayRevenue;
        }

        return view('operator.dashboard', compact(
            'venue',
            'todayBookingsCount',
            'todayRevenue',
            'pendingVerificationCount',
            'todayCancelledCount',
            'todayBookings',
            'courtOccupancies',
            'weeklyRevenue',
            'weeklyTotal'
        ));
    }

    /**
     * Operator confirms a pending booking.
     */
    public function confirm(Booking $booking, Request $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        abort_if($booking->venue_id !== $venue->id, 403, 'Anda tidak memiliki hak akses untuk booking di venue ini.');

        $this->bookingService->confirmByOperator($booking, $request->user());

        return back()->with('status', "Booking {$booking->booking_code} berhasil dikonfirmasi.");
    }

    /**
     * Operator rejects a pending booking with required reason.
     */
    public function reject(Booking $booking, RejectBookingRequest $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        abort_if($booking->venue_id !== $venue->id, 403, 'Anda tidak memiliki hak akses untuk booking di venue ini.');

        $this->bookingService->rejectByOperator($booking, $request->user(), $request->validated('reason'));

        return back()->with('status', "Booking {$booking->booking_code} berhasil ditolak.");
    }

    /**
     * Operator cancels a confirmed booking with required reason.
     */
    public function cancel(Booking $booking, CancelBookingRequest $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        abort_if($booking->venue_id !== $venue->id, 403, 'Anda tidak memiliki hak akses untuk booking di venue ini.');

        $this->bookingService->cancelConfirmedByOperator($booking, $request->user(), $request->validated('reason'));

        return back()->with('status', "Booking {$booking->booking_code} telah dibatalkan dan menunggu proses refund manual.");
    }

    /**
     * Display operator booking list with search, status filters, and pagination.
     */
    public function bookingList(Request $request): View
    {
        $venue = $this->getOperatorVenue($request->user());
        $search = $request->query('search', '');
        $activeFilter = $request->query('status', 'all');

        $query = Booking::with(['items.court', 'user', 'payment'])
            ->where('venue_id', $venue->id);

        if ($activeFilter !== 'all' && ! empty($activeFilter)) {
            $query->where('status', $activeFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $allBookings = $query->latest()->paginate(10)->withQueryString();

        return view('operator.booking', compact('venue', 'allBookings', 'search', 'activeFilter'));
    }

    /**
     * Export filtered bookings to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        $search = $request->query('search', '');
        $activeFilter = $request->query('status', 'all');

        $query = Booking::with(['items.court', 'payment'])
            ->where('venue_id', $venue->id);

        if ($activeFilter !== 'all' && ! empty($activeFilter)) {
            $query->where('status', $activeFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="daftar-booking-'.now()->format('Ymd-His').'.csv"',
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Booking', 'Nama Pemesan', 'Nomor WhatsApp', 'Email', 'Lapangan', 'Tanggal Main', 'Jam Main', 'Total (Rp)', 'Status', 'Metode Bayar', 'Waktu Dibuat']);

            foreach ($bookings as $b) {
                $courtName = $b->items->first()?->court?->name ?? 'Lapangan';
                $bookingDate = $b->items->first()?->booking_date ?? '-';
                $times = $b->items->pluck('start_time')->map(fn ($t) => substr($t, 0, 5))->join(', ');
                $method = $b->payment?->payment_method?->label() ?? ($b->payment?->payment_method?->value ?? '-');

                fputcsv($handle, [
                    $b->booking_code,
                    $b->customer_name,
                    $b->customer_phone,
                    $b->customer_email,
                    $courtName,
                    $bookingDate,
                    $times,
                    $b->total_amount,
                    $b->status->label(),
                    $method,
                    $b->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display multi-court schedule grid with manual blocking/unblocking.
     */
    public function jadwal(Request $request): View
    {
        $venue = $this->getOperatorVenue($request->user());
        $selectedDate = $request->query('date', Carbon::today()->toDateString());
        $selectedCourt = $request->query('court', 'all');

        // Lazy cleanup for this venue and date
        $this->expireStaleBookings->handle(null, $selectedDate);

        $openHour = (int) substr($venue->open_time, 0, 2);
        $closeHour = (int) substr($venue->close_time, 0, 2);
        $hours = range($openHour, $closeHour - 1);

        $courtsQuery = Court::where('venue_id', $venue->id);
        if ($selectedCourt !== 'all' && is_numeric($selectedCourt)) {
            $courtsQuery->where('id', (int) $selectedCourt);
        }
        $courts = $courtsQuery->get();

        $slots = CourtSlot::with(['booking'])
            ->whereIn('court_id', $courts->pluck('id'))
            ->whereDate('booking_date', $selectedDate)
            ->get();

        return view('operator.jadwal', compact(
            'venue',
            'courts',
            'hours',
            'selectedDate',
            'selectedCourt',
            'slots'
        ));
    }

    /**
     * Operator manually blocks a free slot.
     */
    public function blockSlot(BlockSlotRequest $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        $court = Court::where('id', $request->validated('court_id'))
            ->where('venue_id', $venue->id)
            ->firstOrFail();

        $bookingDate = $request->validated('booking_date');
        $rawStartTime = $request->validated('start_time');
        $carbonStart = Carbon::createFromTimeString($rawStartTime);
        $startTime = $carbonStart->format('H:i:00');
        $endTime = $carbonStart->copy()->addHour()->format('H:i:00');

        $now = Carbon::now();

        // Ensure slot is completely free
        $existing = CourtSlot::where('court_id', $court->id)
            ->whereDate('booking_date', $bookingDate)
            ->where('start_time', $startTime)
            ->where(function ($q) use ($now) {
                $q->whereNull('locked_until')
                    ->orWhere('locked_until', '>', $now);
            })
            ->first();

        if ($existing) {
            return back()->withErrors(['start_time' => 'Slot pada jam tersebut sedang terisi atau terblokir.']);
        }

        $blockType = SlotBlockType::from($request->validated('block_type', SlotBlockType::Maintenance->value));

        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => null,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'block_type' => $blockType,
            'note' => $request->validated('note'),
            'locked_until' => null,
        ]);

        return back()->with('status', "Slot {$court->name} pada {$bookingDate} pukul {$startTime} berhasil diblokir.");
    }

    /**
     * Operator unblocks a manually blocked slot.
     */
    public function unblockSlot(int $id, Request $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        $slot = CourtSlot::with('court')->findOrFail($id);

        abort_if($slot->court->venue_id !== $venue->id, 403, 'Akses venue tidak sesuai.');

        // User rule: "hanya slot blokir yang bisa dibuka, tidak slot booking"
        if ($slot->block_type === SlotBlockType::Booking || $slot->booking_id !== null) {
            return back()->withErrors(['error' => 'Slot reservasi customer tidak dapat dibuka langsung. Batalkan booking terlebih dahulu.']);
        }

        $slot->delete();

        return back()->with('status', 'Blokir slot berhasil dibuka.');
    }

    /**
     * Display court management page.
     */
    public function lapangan(Request $request): View
    {
        $venue = $this->getOperatorVenue($request->user());
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $openHour = (int) substr($venue->open_time, 0, 2);
        $closeHour = (int) substr($venue->close_time, 0, 2);
        $totalOperatingHours = max(1, $closeHour - $openHour);

        $courts = Court::where('venue_id', $venue->id)->get();

        $courtStats = [];
        foreach ($courts as $court) {
            if ($court->status === CourtStatus::Maintenance) {
                $courtStats[$court->id] = 0;

                continue;
            }

            $bookedCount = CourtSlot::where('court_id', $court->id)
                ->whereDate('booking_date', $today)
                ->where(function ($q) use ($now) {
                    $q->whereNull('locked_until')
                        ->orWhere('locked_until', '>', $now);
                })
                ->count();

            $courtStats[$court->id] = min(100, (int) round(($bookedCount / $totalOperatingHours) * 100));
        }

        return view('operator.lapangan', compact('venue', 'courts', 'courtStats'));
    }

    /**
     * Store new court with automatic unique slug.
     */
    public function storeCourt(StoreCourtRequest $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());

        $name = $request->validated('name');
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Court::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        Court::create([
            'venue_id' => $venue->id,
            'name' => $name,
            'slug' => $slug,
            'sport_category' => $request->validated('sport_category'),
            'surface_type' => $request->validated('surface_type'),
            'price_regular' => $request->validated('price_regular'),
            'price_prime' => $request->validated('price_prime'),
            'status' => $request->validated('status', CourtStatus::Active->value),
            'specs' => $request->validated('specs'),
        ]);

        return back()->with('status', "Lapangan {$name} berhasil ditambahkan.");
    }

    /**
     * Update existing court details.
     */
    public function updateCourt(Court $court, UpdateCourtRequest $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        abort_if($court->venue_id !== $venue->id, 403, 'Akses venue tidak sesuai.');

        $court->update([
            'name' => $request->validated('name'),
            'sport_category' => $request->validated('sport_category'),
            'surface_type' => $request->validated('surface_type'),
            'price_regular' => $request->validated('price_regular'),
            'price_prime' => $request->validated('price_prime'),
            'status' => $request->validated('status', $court->status->value),
            'specs' => $request->validated('specs', $court->specs),
        ]);

        return back()->with('status', "Data lapangan {$court->name} berhasil diperbarui.");
    }

    /**
     * Toggle court status between active and maintenance (nonaktifkan, bukan hapus).
     */
    public function toggleCourtStatus(Court $court, Request $request): RedirectResponse
    {
        $venue = $this->getOperatorVenue($request->user());
        abort_if($court->venue_id !== $venue->id, 403, 'Akses venue tidak sesuai.');

        $court->status = ($court->status === CourtStatus::Active)
            ? CourtStatus::Maintenance
            : CourtStatus::Active;
        $court->save();

        $statusText = $court->status === CourtStatus::Active ? 'diaktifkan' : 'dinonaktifkan (maintenance)';

        return back()->with('status', "Lapangan {$court->name} berhasil {$statusText}.");
    }
}
