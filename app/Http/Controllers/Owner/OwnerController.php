<?php

namespace App\Http\Controllers\Owner;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OwnerController extends Controller
{
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

        // Venue Cards with stats
        $venueCards = [];
        foreach ($venues as $v) {
            $vBookingsCount = Booking::where('venue_id', $v->id)->count();
            $vRevenue = (int) Booking::where('venue_id', $v->id)
                ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
                ->sum('paid_amount');
            if ($vRevenue === 0) {
                $vRevenue = (int) Booking::where('venue_id', $v->id)
                    ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
                    ->sum('total_amount');
            }
            $vReceivables = (int) Booking::where('venue_id', $v->id)
                ->where('status', BookingStatus::DpPaid)
                ->sum('remaining_amount');

            $venueCards[] = [
                'venue' => $v,
                'courts_count' => $v->courts_count ?? $v->courts()->count(),
                'bookings_count' => $vBookingsCount,
                'revenue' => $vRevenue,
                'receivables' => $vReceivables,
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
     * Export owner bookings to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        ['accessibleVenues' => $venues] = $this->resolveOwnerVenues($user);

        $selectedVenueId = $request->query('venue_id', 'all');
        $allOwnerVenueIds = $venues->pluck('id')->all();
        $targetVenueIds = ($selectedVenueId !== 'all' && in_array((int) $selectedVenueId, $allOwnerVenueIds, true))
            ? [(int) $selectedVenueId]
            : $allOwnerVenueIds;

        $bookings = Booking::with(['venue', 'items.court', 'payment', 'settledBy'])
            ->whereIn('venue_id', $targetVenueIds)
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="owner-laporan-booking-'.now()->format('Ymd-His').'.csv"',
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Booking', 'Venue', 'Nama Pemesan', 'Nomor WA', 'Email', 'Lapangan', 'Tanggal Main', 'Jam Main', 'Total (Rp)', 'DP (Rp)', 'Sisa (Rp)', 'Status', 'Petugas/Kasir']);

            foreach ($bookings as $b) {
                $courtName = $b->items->first()?->court?->name ?? 'Lapangan';
                $bookingDate = $b->items->first()?->booking_date ?? '-';
                $times = $b->items->pluck('start_time')->map(fn ($t) => substr($t, 0, 5))->join(', ');

                fputcsv($handle, [
                    $b->booking_code,
                    $b->venue->name,
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
                    $b->settledBy?->name ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
