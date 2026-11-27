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
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use App\Services\BookingService;
use App\Services\ExpireStaleBookings;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
     * Resolve the single venue that the operator / cashier is strictly assigned to.
     * Prevents cross-venue tampering or access.
     *
     * @return array{activeVenue: Venue, accessibleVenues: Collection<int, Venue>}
     */
    protected function resolveVenues(User $user, ?int $requestedVenueId = null): array
    {
        if ($user->isSuperAdmin()) {
            $accessibleVenues = Venue::all();
            $activeVenue = $requestedVenueId
                ? ($accessibleVenues->firstWhere('id', $requestedVenueId) ?? $accessibleVenues->first())
                : $accessibleVenues->first();

            if (! $activeVenue) {
                abort(404, 'Venue belum terdaftar dalam sistem.');
            }

            return ['activeVenue' => $activeVenue, 'accessibleVenues' => $accessibleVenues];
        }

        // KASIR / OPERATOR: WAJIB TERIKAT HANYA PADA VENUE TEMPATNYA BERTUGAS
        abort_if(! $user->venue_id, 403, 'Akun kasir Anda belum ditugaskan ke venue manapun. Silakan hubungi pemilik venue.');

        // Proteksi anti lintas venue: Kasir dilarang memanipulasi parameter venue_id
        if ($requestedVenueId && (int) $requestedVenueId !== (int) $user->venue_id) {
            abort(403, 'Akses ditolak: Anda hanya berwenang mengakses venue tempat Anda ditugaskan.');
        }

        $venue = Venue::find($user->venue_id);
        if (! $venue) {
            abort(404, 'Venue penugasan tidak ditemukan.');
        }

        return ['activeVenue' => $venue, 'accessibleVenues' => collect([$venue])];
    }

    /**
     * Display operator dashboard with KPIs and today's incoming bookings for their assigned venue.
     */
    public function dashboard(Request $request): View
    {
        ['activeVenue' => $venue, 'accessibleVenues' => $accessibleVenues] = $this->resolveVenues($request->user());

        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // Trigger lazy cleaner for today's venue slots
        $this->expireStaleBookings->handle(null, $today);

        // KPI 1: Today's Bookings Count
        $todayBookingsCount = Booking::where('venue_id', $venue->id)
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->count();

        // KPI 2: Today's Revenue (confirmed, dp_paid, paid_in_full, done)
        $todayRevenue = (int) Booking::where('venue_id', $venue->id)
            ->whereIn('status', [
                BookingStatus::Confirmed,
                BookingStatus::PaidInFull,
                BookingStatus::DpPaid,
                BookingStatus::Done,
                BookingStatus::Completed,
            ])
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->sum('paid_amount');

        if ($todayRevenue === 0) {
            $todayRevenue = (int) Booking::where('venue_id', $venue->id)
                ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
                ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
                ->sum('total_amount');
        }

        // KPI 3: Pending verification / confirmation queue
        $pendingVerificationCount = Booking::where('venue_id', $venue->id)
            ->where(function ($q) {
                $q->whereIn('status', [BookingStatus::Pending, BookingStatus::PendingPayment])
                    ->orWhereHas('payment', fn ($pq) => $pq->where('status', PaymentStatus::PendingVerification));
            })
            ->count();

        // KPI 4: Cancelled / Expired bookings today
        $todayCancelledCount = Booking::where('venue_id', $venue->id)
            ->whereIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->count();

        // Incoming bookings today (latest 10)
        $todayBookings = Booking::with(['items.court', 'user', 'payment', 'payments'])
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
                ->whereIn('status', [
                    BookingStatus::Confirmed,
                    BookingStatus::PaidInFull,
                    BookingStatus::DpPaid,
                    BookingStatus::Done,
                    BookingStatus::Completed,
                ])
                ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $dayDate))
                ->sum('paid_amount');

            if ($dayRevenue === 0) {
                $dayRevenue = (int) Booking::where('venue_id', $venue->id)
                    ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
                    ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $dayDate))
                    ->sum('total_amount');
            }

            $weeklyRevenue[] = [
                'date' => $dayDate,
                'label' => Carbon::parse($dayDate)->isoFormat('dd'),
                'amount' => $dayRevenue,
            ];

            $weeklyTotal += $dayRevenue;
        }

        return view('operator.dashboard', compact(
            'venue',
            'accessibleVenues',
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
     * Validate e-ticket QR/token or booking code for customer check-in at the venue.
     */
    public function checkIn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:120'],
        ]);

        $token = trim($validated['token']);
        ['accessibleVenues' => $accessibleVenues] = $this->resolveVenues($request->user());
        $venueIds = $accessibleVenues->pluck('id')->all();

        $booking = Booking::with(['items.court', 'venue'])
            ->whereIn('venue_id', $venueIds)
            ->where(function ($q) use ($token) {
                $q->where('checkin_token', $token)
                    ->orWhere('booking_code', $token);
            })
            ->first();

        if (! $booking) {
            return back()->withErrors(['checkin' => 'Token Check-In atau Kode Booking tidak ditemukan di venue Anda.']);
        }

        if (in_array($booking->status, [BookingStatus::Cancelled, BookingStatus::Expired, BookingStatus::Pending, BookingStatus::PendingPayment], true)) {
            return back()->withErrors(['checkin' => "Booking #{$booking->booking_code} belum lunas / sudah dibatalkan, tidak dapat melakukan check-in."]);
        }

        if ($booking->status === BookingStatus::DpPaid && $booking->remaining_amount > 0) {
            return back()->withErrors(['checkin' => "Booking #{$booking->booking_code} masih berstatus DP. Harap proses pelunasan Rp ".number_format($booking->remaining_amount, 0, ',', '.').' di kasir terlebih dahulu.']);
        }

        if ($booking->checked_in_at !== null) {
            return back()->withErrors(['checkin' => "Booking #{$booking->booking_code} sudah melakukan check-in pada ".$booking->checked_in_at->format('d M Y H:i').'.']);
        }

        $booking->checked_in_at = Carbon::now();
        $booking->checked_in_by = $request->user()->id;
        $booking->save();

        return back()->with('status', "Check-In berhasil! Booking #{$booking->booking_code} ({$booking->customer_name}) tervalidasi.");
    }

    /**
     * Operator confirms a pending booking for their venue only.
     */
    public function confirm(Booking $booking, Request $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());
        abort_if((int) $booking->venue_id !== (int) $venue->id, 403, 'Akses ditolak: Anda hanya berwenang pada venue Anda.');

        $this->bookingService->confirmByOperator($booking, $request->user());

        return back()->with('status', "Booking {$booking->booking_code} berhasil diverifikasi & dikonfirmasi.");
    }

    /**
     * Process on-site settlement for a DP-paid booking in their venue only.
     */
    public function settle(Booking $booking, Request $request): RedirectResponse
    {
        $user = $request->user();
        ['activeVenue' => $venue] = $this->resolveVenues($user);

        abort_if((int) $booking->venue_id !== (int) $venue->id, 403, 'Akses ditolak: Anda hanya berwenang memproses pelunasan di venue tempat Anda ditugaskan.');

        $validated = $request->validate([
            'settlement_method' => ['required', 'string', 'in:cash,qris,manual_transfer'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $remaining = $booking->remaining_amount;

        $this->bookingService->settlePaymentByOperator(
            $booking,
            $user,
            $validated['settlement_method'],
            $validated['notes'] ?? null
        );

        return back()->with('status', "Pelunasan sisa tagihan booking {$booking->booking_code} sebesar Rp ".number_format($remaining, 0, ',', '.').' berhasil dicatat oleh Kasir!');
    }

    /**
     * Operator rejects a pending booking in their venue only.
     */
    public function reject(Booking $booking, RejectBookingRequest $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());
        abort_if((int) $booking->venue_id !== (int) $venue->id, 403, 'Akses ditolak: Anda hanya berwenang pada venue Anda.');

        $this->bookingService->rejectByOperator($booking, $request->user(), $request->validated('reason'));

        return back()->with('status', "Booking {$booking->booking_code} berhasil ditolak.");
    }

    /**
     * Operator cancels a confirmed booking in their venue only.
     */
    public function cancel(Booking $booking, CancelBookingRequest $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());
        abort_if((int) $booking->venue_id !== (int) $venue->id, 403, 'Akses ditolak: Anda hanya berwenang pada venue Anda.');

        $this->bookingService->cancelConfirmedByOperator($booking, $request->user(), $request->validated('reason'));

        return back()->with('status', "Booking {$booking->booking_code} telah dibatalkan dan menunggu proses refund manual.");
    }

    /**
     * Display operator booking list strictly for their assigned venue.
     */
    public function bookingList(Request $request): View
    {
        ['activeVenue' => $venue, 'accessibleVenues' => $accessibleVenues] = $this->resolveVenues($request->user());

        $search = $request->query('search', '');
        $activeFilter = $request->query('status', 'all');

        $query = Booking::with(['items.court', 'user', 'payment', 'payments', 'settledBy'])
            ->where('venue_id', $venue->id);

        if ($activeFilter !== 'all' && ! empty($activeFilter)) {
            if ($activeFilter === 'pending') {
                $query->whereIn('status', [BookingStatus::Pending, BookingStatus::PendingPayment]);
            } elseif ($activeFilter === 'confirmed') {
                $query->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull]);
            } elseif ($activeFilter === 'done') {
                $query->whereIn('status', [BookingStatus::Done, BookingStatus::Completed]);
            } else {
                $query->where('status', $activeFilter);
            }
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $allBookings = $query->latest()->paginate(10)->withQueryString();

        return view('operator.booking', compact('venue', 'accessibleVenues', 'allBookings', 'search', 'activeFilter'));
    }

    /**
     * Financial Report strictly for the cashier's assigned venue.
     */
    public function laporan(Request $request): View
    {
        $user = $request->user();
        ['activeVenue' => $venue, 'accessibleVenues' => $accessibleVenues] = $this->resolveVenues($user);

        $period = $request->query('period', 'bulanan');
        $today = Carbon::today();

        switch ($period) {
            case 'harian':
                $startDate = $today->copy()->startOfDay();
                $endDate = $today->copy()->endOfDay();
                $periodLabel = 'Hari Ini ('.$today->translatedFormat('d F Y').')';
                break;
            case 'mingguan':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $periodLabel = 'Minggu Ini ('.$startDate->translatedFormat('d M').' - '.$endDate->translatedFormat('d M Y').')';
                break;
            case 'custom':
                $startDate = $request->query('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
                $endDate = $request->query('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : Carbon::now()->endOfDay();
                $periodLabel = 'Periode '.$startDate->translatedFormat('d M Y').' s/d '.$endDate->translatedFormat('d M Y');
                break;
            case 'bulanan':
            default:
                $period = 'bulanan';
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $periodLabel = 'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')';
                break;
        }

        // Strictly single venue
        $targetVenueIds = [$venue->id];

        // 1. Total Nilai Transaksi (Gross Revenue)
        $grossRevenue = (int) Booking::whereIn('venue_id', $targetVenueIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->sum('total_amount');

        // 2. Uang Masuk dari DP
        $dpIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'dp')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 3. Uang Masuk dari Pelunasan On-Site
        $settlementIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'settlement')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 4. Uang Masuk dari Full Payment
        $fullPaymentIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'full')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Total Kas Masuk Riil
        $totalCashIn = $dpIncome + $settlementIncome + $fullPaymentIncome;

        // 5. Sisa Tagihan (Piutang / Belum Lunas)
        $receivables = (int) Booking::whereIn('venue_id', $targetVenueIds)
            ->where('status', BookingStatus::DpPaid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('remaining_amount');

        // List Bookings in period
        $reportBookings = Booking::with(['items.court', 'user', 'payments', 'settledBy'])
            ->whereIn('venue_id', $targetVenueIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('operator.laporan', compact(
            'venue',
            'accessibleVenues',
            'period',
            'periodLabel',
            'startDate',
            'endDate',
            'grossRevenue',
            'dpIncome',
            'settlementIncome',
            'fullPaymentIncome',
            'totalCashIn',
            'receivables',
            'reportBookings'
        ));
    }

    /**
     * Export filtered bookings to CSV for their venue only.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());

        $search = $request->query('search', '');
        $activeFilter = $request->query('status', 'all');

        $query = Booking::with(['items.court', 'payment', 'payments', 'settledBy'])
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
            'Content-Disposition' => 'attachment; filename="kasir-booking-'.now()->format('Ymd-His').'.csv"',
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Booking', 'Nama Pemesan', 'Nomor WhatsApp', 'Email', 'Lapangan', 'Tanggal Main', 'Jam Main', 'Total (Rp)', 'Uang Muka/DP (Rp)', 'Sisa (Rp)', 'Status', 'Metode Bayar', 'Waktu Dibuat']);

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
                    $b->paid_amount,
                    $b->remaining_amount,
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
     * Display multi-court schedule grid for their venue only.
     */
    public function jadwal(Request $request): View
    {
        ['activeVenue' => $venue, 'accessibleVenues' => $accessibleVenues] = $this->resolveVenues($request->user());

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
            'accessibleVenues',
            'courts',
            'hours',
            'selectedDate',
            'selectedCourt',
            'slots'
        ));
    }

    /**
     * Operator manually blocks a free slot for their venue only.
     */
    public function blockSlot(BlockSlotRequest $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());
        $court = Court::where('id', $request->validated('court_id'))
            ->where('venue_id', $venue->id)
            ->firstOrFail();

        $bookingDate = $request->validated('booking_date');
        $rawStartTime = $request->validated('start_time');
        $carbonStart = Carbon::createFromTimeString($rawStartTime);
        $startTime = $carbonStart->format('H:i:00');
        $endTime = $carbonStart->copy()->addHour()->format('H:i:00');

        $now = Carbon::now();

        // Check if slot is occupied
        $occupied = CourtSlot::where('court_id', $court->id)
            ->whereDate('booking_date', $bookingDate)
            ->where('start_time', $startTime)
            ->where(function ($q) use ($now) {
                $q->whereNull('locked_until')
                    ->orWhere('locked_until', '>', $now);
            })
            ->exists();

        if ($occupied) {
            return back()->withErrors(['slot' => 'Slot waktu tersebut sudah terisi atau diblokir.']);
        }

        CourtSlot::create([
            'court_id' => $court->id,
            'booking_id' => null,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'block_type' => SlotBlockType::Maintenance,
            'locked_until' => null,
            'notes' => $request->validated('reason') ?? 'Diblokir oleh operator',
        ]);

        return back()->with('status', "Slot {$startTime} pada {$court->name} berhasil diblokir.");
    }

    /**
     * Operator unblocks a previously blocked slot for their venue only.
     */
    public function unblockSlot(int $id, Request $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());

        $slot = CourtSlot::where('id', $id)
            ->whereHas('court', fn ($q) => $q->where('venue_id', $venue->id))
            ->where('block_type', SlotBlockType::Maintenance)
            ->firstOrFail();

        $slot->delete();

        return back()->with('status', 'Blokir slot berhasil dibuka.');
    }

    /**
     * Display court list for their venue only.
     */
    public function lapangan(Request $request): View
    {
        ['activeVenue' => $venue, 'accessibleVenues' => $accessibleVenues] = $this->resolveVenues($request->user());

        $courts = Court::where('venue_id', $venue->id)
            ->withCount(['bookingItems'])
            ->latest()
            ->paginate(10);

        return view('operator.lapangan', compact('venue', 'accessibleVenues', 'courts'));
    }

    /**
     * Store new court for their venue only.
     */
    public function storeCourt(StoreCourtRequest $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());

        $slug = Str::slug($request->validated('name'));
        if (Court::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(4);
        }

        Court::create([
            'venue_id' => $venue->id,
            'name' => $request->validated('name'),
            'slug' => $slug,
            'sport_type' => $request->validated('sport_type'),
            'floor_type' => $request->validated('floor_type'),
            'is_indoor' => $request->validated('is_indoor'),
            'price_regular' => $request->validated('price_regular'),
            'price_prime' => $request->validated('price_prime'),
            'status' => CourtStatus::Active,
        ]);

        return back()->with('status', 'Lapangan baru berhasil ditambahkan.');
    }

    /**
     * Update court configuration for their venue only.
     */
    public function updateCourt(Court $court, UpdateCourtRequest $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());
        abort_if((int) $court->venue_id !== (int) $venue->id, 403, 'Akses ditolak: Lapangan ini bukan milik venue Anda.');

        $court->update([
            'name' => $request->validated('name'),
            'sport_type' => $request->validated('sport_type'),
            'floor_type' => $request->validated('floor_type'),
            'is_indoor' => $request->validated('is_indoor'),
            'price_regular' => $request->validated('price_regular'),
            'price_prime' => $request->validated('price_prime'),
        ]);

        return back()->with('status', "Data lapangan {$court->name} berhasil diperbarui.");
    }

    /**
     * Toggle court operational status for their venue only.
     */
    public function toggleCourtStatus(Court $court, Request $request): RedirectResponse
    {
        ['activeVenue' => $venue] = $this->resolveVenues($request->user());
        abort_if((int) $court->venue_id !== (int) $venue->id, 403, 'Akses ditolak: Lapangan ini bukan milik venue Anda.');

        $newStatus = $court->status === CourtStatus::Active ? CourtStatus::Maintenance : CourtStatus::Active;
        $court->status = $newStatus;
        $court->save();

        $label = $newStatus === CourtStatus::Active ? 'diaktifkan kembali' : 'dialihkan ke mode perawatan (maintenance)';

        return back()->with('status', "Lapangan {$court->name} berhasil {$label}.");
    }
}
