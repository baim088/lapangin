<?php

namespace App\Http\Controllers\Owner;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreCourtRequest;
use App\Http\Requests\Owner\UpdateCourtRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use App\Services\ReportExportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OwnerController extends Controller
{
    public function __construct(
        protected ReportExportService $reportExportService,
    ) {}
    /**
     * Resolve venues owned by this user (or all if super admin).
     *
     * @return array{activeVenue: Venue, accessibleVenues: Collection<int, Venue>}
     */
    protected function resolveOwnerVenues(User $user, ?int $requestedVenueId = null): array
    {
        if ($user->isSuperAdmin()) {
            $accessibleVenues = Venue::withCount('courts')->get();
        } else {
            $accessibleVenues = $user->ownedVenues()->withCount('courts')->get();
        }

        if ($accessibleVenues->isEmpty()) {
            abort(403, 'Akun Owner Anda belum memiliki venue yang terdaftar.');
        }

        $activeVenue = $requestedVenueId
            ? ($accessibleVenues->firstWhere('id', $requestedVenueId) ?? $accessibleVenues->first())
            : $accessibleVenues->first();

        return ['activeVenue' => $activeVenue, 'accessibleVenues' => $accessibleVenues];
    }

    /**
     * Owner Executive Dashboard: Overview of all owned venues, aggregated revenue, and today's activity.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        ['activeVenue' => $activeVenue, 'accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $venueIds = $venues->pluck('id')->all();
        $today = Carbon::today()->toDateString();
        $thisMonth = Carbon::now()->startOfMonth();

        // 1. Business Level Metrics across all owned venues
        $totalVenuesCount = $venues->count();
        $totalCourtsCount = Court::whereIn('venue_id', $venueIds)->count();

        // Total Omzet Bulan Ini (Semua venue milik owner)
        $monthRevenue = (int) Booking::whereIn('venue_id', $venueIds)
            ->where('created_at', '>=', $thisMonth)
            ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->sum('paid_amount');

        if ($monthRevenue === 0) {
            $monthRevenue = (int) Booking::whereIn('venue_id', $venueIds)
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
                ->sum('total_amount');
        }

        // Today's Bookings Count
        $todayBookingsCount = Booking::whereIn('venue_id', $venueIds)
            ->whereHas('items', fn ($q) => $q->whereDate('booking_date', $today))
            ->count();

        // Piutang aktif (DP Paid yang belum lunas)
        $totalReceivables = (int) Booking::whereIn('venue_id', $venueIds)
            ->where('status', BookingStatus::DpPaid)
            ->sum('remaining_amount');

        // Total Kas Masuk Riil Akumulatif
        $totalRealCashIn = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $venueIds))
            ->where('status', PaymentStatus::Paid)
            ->sum('amount');

        // Venue Cards with stats (grouped queries to avoid N+1 per venue)
        $bookingCountsByVenue = Booking::whereIn('venue_id', $venueIds)
            ->selectRaw('venue_id, COUNT(*) as total')
            ->groupBy('venue_id')
            ->pluck('total', 'venue_id');

        $paidRevenueByVenue = Booking::whereIn('venue_id', $venueIds)
            ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->selectRaw('venue_id, SUM(paid_amount) as total')
            ->groupBy('venue_id')
            ->pluck('total', 'venue_id');

        $fallbackRevenueByVenue = Booking::whereIn('venue_id', $venueIds)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
            ->selectRaw('venue_id, SUM(total_amount) as total')
            ->groupBy('venue_id')
            ->pluck('total', 'venue_id');

        $receivablesByVenue = Booking::whereIn('venue_id', $venueIds)
            ->where('status', BookingStatus::DpPaid)
            ->selectRaw('venue_id, SUM(remaining_amount) as total')
            ->groupBy('venue_id')
            ->pluck('total', 'venue_id');

        $venueCards = [];
        foreach ($venues as $v) {
            $vRevenue = (int) ($paidRevenueByVenue[$v->id] ?? 0);
            if ($vRevenue === 0) {
                $vRevenue = (int) ($fallbackRevenueByVenue[$v->id] ?? 0);
            }

            $venueCards[] = [
                'venue' => $v,
                'courts_count' => $v->courts_count ?? 0,
                'bookings_count' => (int) ($bookingCountsByVenue[$v->id] ?? 0),
                'revenue' => $vRevenue,
                'receivables' => (int) ($receivablesByVenue[$v->id] ?? 0),
            ];
        }

        // Latest 10 Bookings across all owned venues
        $recentBookings = Booking::with(['venue', 'items.court', 'user', 'settledBy'])
            ->whereIn('venue_id', $venueIds)
            ->latest()
            ->take(10)
            ->get();

        return view('owner.dashboard', compact(
            'venues',
            'activeVenue',
            'totalVenuesCount',
            'totalCourtsCount',
            'monthRevenue',
            'todayBookingsCount',
            'totalReceivables',
            'totalRealCashIn',
            'venueCards',
            'recentBookings'
        ));
    }

    /**
     * Financial Report specifically for Venue Owner.
     */
    public function laporan(Request $request): View
    {
        $user = $request->user();
        ['activeVenue' => $activeVenue, 'accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $selectedVenueId = $request->query('venue_id', 'all');
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

        // Scope to chosen owned venue or all owned venues
        $allOwnerVenueIds = $venues->pluck('id')->all();
        $targetVenueIds = ($selectedVenueId !== 'all' && in_array((int) $selectedVenueId, $allOwnerVenueIds, true))
            ? [(int) $selectedVenueId]
            : $allOwnerVenueIds;

        // 1. Gross Revenue
        $grossRevenue = (int) Booking::whereIn('venue_id', $targetVenueIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->sum('total_amount');

        // 2. DP Income
        $dpIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'dp')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 3. Settlement Income
        $settlementIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'settlement')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 4. Full Payment Income
        $fullPaymentIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'full')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Total Kas Masuk Riil
        $totalCashIn = $dpIncome + $settlementIncome + $fullPaymentIncome;

        // 5. Sisa Tagihan (Piutang)
        $receivables = (int) Booking::whereIn('venue_id', $targetVenueIds)
            ->where('status', BookingStatus::DpPaid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('remaining_amount');

        // Bookings in this period
        $reportBookings = Booking::with(['venue', 'items.court', 'user', 'payments', 'settledBy'])
            ->whereIn('venue_id', $targetVenueIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('owner.laporan', compact(
            'venues',
            'selectedVenueId',
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
     * Booking Monitoring List for Owner.
     */
    public function bookingList(Request $request): View
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $selectedVenueId = $request->query('venue_id', 'all');
        $search = $request->query('search', '');
        $activeFilter = $request->query('status', 'all');

        $allOwnerVenueIds = $venues->pluck('id')->all();
        $targetVenueIds = ($selectedVenueId !== 'all' && in_array((int) $selectedVenueId, $allOwnerVenueIds, true))
            ? [(int) $selectedVenueId]
            : $allOwnerVenueIds;

        $query = Booking::with(['venue', 'items.court', 'user', 'payment', 'settledBy'])
            ->whereIn('venue_id', $targetVenueIds);

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

        $allBookings = $query->latest()->paginate(12)->withQueryString();

        return view('owner.booking', compact('venues', 'selectedVenueId', 'allBookings', 'search', 'activeFilter'));
    }

    /**
     * Court Management for Owner.
     */
    public function lapangan(Request $request): View
    {
        $user = $request->user();
        ['activeVenue' => $activeVenue, 'accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $selectedVenueId = $request->query('venue_id', (string) $activeVenue->id);
        $allOwnerVenueIds = $venues->pluck('id')->all();

        $targetVenueId = in_array((int) $selectedVenueId, $allOwnerVenueIds, true)
            ? (int) $selectedVenueId
            : $activeVenue->id;

        $currentVenue = $venues->firstWhere('id', $targetVenueId) ?? $activeVenue;

        $courts = Court::where('venue_id', $targetVenueId)
            ->withCount(['bookingItems'])
            ->latest()
            ->paginate(10);

        return view('owner.lapangan', compact('venues', 'currentVenue', 'courts'));
    }

    /**
     * Store a new court and update venue operating & prime hours for the selected owned venue.
     */
    public function storeCourt(StoreCourtRequest $request): RedirectResponse
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $venueId = (int) $request->validated('venue_id');
        $venue = $venues->firstWhere('id', $venueId);
        abort_if(! $venue, 403, 'Akses ditolak: Anda tidak memiliki akses ke venue ini.');

        $slug = Str::slug($request->validated('name'));
        if (Court::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(4);
        }

        Court::create([
            'venue_id' => $venue->id,
            'name' => $request->validated('name'),
            'slug' => $slug,
            'sport_category' => $request->validated('sport_category'),
            'surface_type' => $request->validated('surface_type'),
            'price_regular' => $request->validated('price_regular'),
            'price_prime' => $request->validated('price_prime'),
            'status' => CourtStatus::Active,
        ]);

        $venue->update([
            'open_time' => $request->validated('open_time').':00',
            'close_time' => $request->validated('close_time').':00',
            'prime_start_time' => $request->validated('prime_start_time').':00',
            'prime_end_time' => $request->validated('prime_end_time').':00',
        ]);

        return back()->with('status', 'Lapangan baru serta jam operasional & prime venue berhasil disimpan.');
    }

    /**
     * Update court configuration and venue operating & prime hours for an owned venue.
     */
    public function updateCourt(Court $court, UpdateCourtRequest $request): RedirectResponse
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $venue = $venues->firstWhere('id', (int) $court->venue_id);
        abort_if(! $venue, 403, 'Akses ditolak: Lapangan ini bukan milik venue Anda.');

        $court->update([
            'name' => $request->validated('name'),
            'sport_category' => $request->validated('sport_category'),
            'surface_type' => $request->validated('surface_type'),
            'price_regular' => $request->validated('price_regular'),
            'price_prime' => $request->validated('price_prime'),
        ]);

        $venue->update([
            'open_time' => $request->validated('open_time').':00',
            'close_time' => $request->validated('close_time').':00',
            'prime_start_time' => $request->validated('prime_start_time').':00',
            'prime_end_time' => $request->validated('prime_end_time').':00',
        ]);

        return back()->with('status', "Data lapangan {$court->name} & jam operasional berhasil diperbarui.");
    }

    /**
     * Toggle court operational status (Active / Maintenance) for an owned venue.
     */
    public function toggleCourtStatus(Court $court, Request $request): RedirectResponse
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        abort_if(! $venues->contains('id', (int) $court->venue_id), 403, 'Akses ditolak: Lapangan ini bukan milik venue Anda.');

        $newStatus = $court->status === CourtStatus::Active ? CourtStatus::Maintenance : CourtStatus::Active;
        $court->status = $newStatus;
        $court->save();

        $label = $newStatus === CourtStatus::Active ? 'diaktifkan kembali' : 'dialihkan ke mode perawatan (maintenance)';

        return back()->with('status', "Lapangan {$court->name} berhasil {$label}.");
    }

    /**
     * Export owner bookings to CSV or formal text-based PDF.
     */
    public function exportCsv(Request $request): StreamedResponse|Response
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $selectedVenueId = (string) $request->query('venue_id', 'all');
        $search = (string) $request->query('search', '');
        $activeFilter = (string) $request->query('status', 'all');
        $format = strtolower((string) $request->query('format', 'csv'));

        $allOwnerVenueIds = $venues->pluck('id')->all();
        $targetVenueIds = ($selectedVenueId !== 'all' && in_array((int) $selectedVenueId, $allOwnerVenueIds, true))
            ? [(int) $selectedVenueId]
            : $allOwnerVenueIds;

        $query = Booking::with(['venue', 'items.court', 'payment', 'payments', 'settledBy'])
            ->whereIn('venue_id', $targetVenueIds);

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

        $bookings = $query->latest()->get();
        $timestamp = now()->format('Ymd-His');

        if ($format === 'pdf') {
            $scopeLabel = $selectedVenueId === 'all'
                ? 'Semua Venue Milik Saya ('.$venues->count().' venue)'
                : ($venues->firstWhere('id', (int) $selectedVenueId)?->name ?? 'Venue Terpilih');

            $filterParts = ['Status: '.($activeFilter === 'all' ? 'Semua' : strtoupper($activeFilter))];
            if ($search !== '') {
                $filterParts[] = 'Pencarian: "'.$search.'"';
            }

            return $this->reportExportService->exportBookingsPdf(
                "owner-booking-{$timestamp}.pdf",
                [
                    'title' => 'Laporan Monitoring Reservasi Booking',
                    'subtitle' => 'Daftar transaksi reservasi seluruh venue milik owner',
                    'scope' => $scopeLabel,
                    'filter' => implode(' | ', $filterParts),
                    'printed_by' => $user->name.' ('.$user->role->label().')',
                    'printed_at' => now()->translatedFormat('d M Y H:i'),
                ],
                $this->reportExportService->buildSummary($bookings),
                $bookings,
                true
            );
        }

        return $this->reportExportService->exportBookingsCsv(
            "owner-laporan-booking-{$timestamp}.csv",
            $bookings,
            true
        );
    }

    /**
     * Export owner financial report to CSV or formal text-based PDF.
     */
    public function exportLaporan(Request $request): StreamedResponse|Response
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $selectedVenueId = (string) $request->query('venue_id', 'all');
        $format = strtolower((string) $request->query('format', 'csv'));

        [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
        ] = $this->reportExportService->resolvePeriod($request);

        $allOwnerVenueIds = $venues->pluck('id')->all();
        $targetVenueIds = ($selectedVenueId !== 'all' && in_array((int) $selectedVenueId, $allOwnerVenueIds, true))
            ? [(int) $selectedVenueId]
            : $allOwnerVenueIds;

        $grossRevenue = (int) Booking::whereIn('venue_id', $targetVenueIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->sum('total_amount');

        $dpIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'dp')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $settlementIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'settlement')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $fullPaymentIncome = (int) Payment::whereHas('booking', fn ($q) => $q->whereIn('venue_id', $targetVenueIds))
            ->where('payment_type', 'full')
            ->where('status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalCashIn = $dpIncome + $settlementIncome + $fullPaymentIncome;

        $receivables = (int) Booking::whereIn('venue_id', $targetVenueIds)
            ->where('status', BookingStatus::DpPaid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('remaining_amount');

        $bookings = Booking::with(['venue', 'items.court', 'user', 'payment', 'payments', 'settledBy'])
            ->whereIn('venue_id', $targetVenueIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $timestamp = now()->format('Ymd-His');

        if ($format === 'pdf') {
            $scopeLabel = $selectedVenueId === 'all'
                ? 'Semua Venue Milik Saya ('.$venues->count().' venue)'
                : ($venues->firstWhere('id', (int) $selectedVenueId)?->name ?? 'Venue Terpilih');

            $summary = $this->reportExportService->buildSummary($bookings, [
                'grossRevenue' => $grossRevenue,
                'dpIncome' => $dpIncome,
                'settlementIncome' => $settlementIncome,
                'fullPaymentIncome' => $fullPaymentIncome,
                'totalCashIn' => $totalCashIn,
                'receivables' => $receivables,
            ]);

            return $this->reportExportService->exportBookingsPdf(
                "owner-laporan-keuangan-{$timestamp}.pdf",
                [
                    'title' => 'Laporan Keuangan Owner',
                    'subtitle' => 'Transparansi arus kas riil, DP masuk, pelunasan di lokasi, dan sisa piutang',
                    'scope' => $scopeLabel,
                    'filter' => $periodLabel,
                    'printed_by' => $user->name.' ('.$user->role->label().')',
                    'printed_at' => now()->translatedFormat('d M Y H:i'),
                ],
                $summary,
                $bookings,
                true
            );
        }

        return $this->reportExportService->exportBookingsCsv(
            "owner-laporan-keuangan-{$timestamp}.csv",
            $bookings,
            true
        );
    }
}
