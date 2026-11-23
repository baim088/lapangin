<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Http\Requests\Admin\UpdateVenueRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display Super Admin overview dashboard with global KPIs and platform health.
     */
    public function dashboard(): View
    {
        $totalVenues = Venue::count();
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalRevenue = (int) Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::PaidInFull,
            BookingStatus::DpPaid,
            BookingStatus::Done,
            BookingStatus::Completed,
        ])->sum('paid_amount');

        if ($totalRevenue === 0) {
            $totalRevenue = (int) Booking::whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])->sum('total_amount');
        }

        $recentUsers = User::latest()->take(5)->get();

        $venues = Venue::withCount(['courts'])->with('owner')->get();

        $venueStats = [];
        foreach ($venues as $v) {
            $venueBookingsCount = Booking::where('venue_id', $v->id)->count();
            $venueRevenue = (int) Booking::where('venue_id', $v->id)
                ->whereIn('status', [
                    BookingStatus::Confirmed,
                    BookingStatus::PaidInFull,
                    BookingStatus::DpPaid,
                    BookingStatus::Done,
                    BookingStatus::Completed,
                ])
                ->sum('paid_amount');

            if ($venueRevenue === 0) {
                $venueRevenue = (int) Booking::where('venue_id', $v->id)
                    ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
                    ->sum('total_amount');
            }

            $venueStats[$v->id] = [
                'bookings_count' => $venueBookingsCount,
                'revenue' => $venueRevenue,
            ];
        }

        return view('admin.dashboard', compact(
            'totalVenues',
            'totalUsers',
            'totalBookings',
            'totalRevenue',
            'recentUsers',
            'venues',
            'venueStats'
        ));
    }

    /**
     * Platform Global Financial Report.
     */
    public function laporan(Request $request): View
    {
        $venues = Venue::all();
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

        $bookingQuery = Booking::query()->whereBetween('created_at', [$startDate, $endDate]);
        $paymentQuery = Payment::query()->where('status', PaymentStatus::Paid)->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedVenueId !== 'all' && is_numeric($selectedVenueId)) {
            $bookingQuery->where('venue_id', (int) $selectedVenueId);
            $paymentQuery->whereHas('booking', fn ($q) => $q->where('venue_id', (int) $selectedVenueId));
        }

        // 1. Gross Revenue
        $grossRevenue = (int) (clone $bookingQuery)
            ->whereNotIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->sum('total_amount');

        // 2. DP Income
        $dpIncome = (int) (clone $paymentQuery)
            ->where('payment_type', 'dp')
            ->sum('amount');

        // 3. Settlement Income
        $settlementIncome = (int) (clone $paymentQuery)
            ->where('payment_type', 'settlement')
            ->sum('amount');

        // 4. Full Payment Income
        $fullPaymentIncome = (int) (clone $paymentQuery)
            ->where('payment_type', 'full')
            ->sum('amount');

        // Total Kas Masuk
        $totalCashIn = $dpIncome + $settlementIncome + $fullPaymentIncome;

        // 5. Piutang (Remaining)
        $receivables = (int) (clone $bookingQuery)
            ->where('status', BookingStatus::DpPaid)
            ->sum('remaining_amount');

        $reportBookings = (clone $bookingQuery)
            ->with(['venue', 'items.court', 'user', 'payments', 'settledBy'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.laporan', compact(
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
     * Display venue list for admin management.
     */
    public function venues(): View
    {
        $venues = Venue::withCount(['courts'])->with('owner')->get();

        $venueStats = [];
        foreach ($venues as $v) {
            $venueBookingsCount = Booking::where('venue_id', $v->id)->count();
            $venueRevenue = (int) Booking::where('venue_id', $v->id)
                ->whereIn('status', [
                    BookingStatus::Confirmed,
                    BookingStatus::PaidInFull,
                    BookingStatus::DpPaid,
                    BookingStatus::Done,
                    BookingStatus::Completed,
                ])
                ->sum('paid_amount');

            if ($venueRevenue === 0) {
                $venueRevenue = (int) Booking::where('venue_id', $v->id)
                    ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::PaidInFull, BookingStatus::Done, BookingStatus::Completed])
                    ->sum('total_amount');
            }

            $venueUsersCount = User::where('venue_id', $v->id)->count();

            $venueStats[$v->id] = [
                'bookings_count' => $venueBookingsCount,
                'revenue' => $venueRevenue,
                'users_count' => $venueUsersCount,
            ];
        }

        return view('admin.venues', compact('venues', 'venueStats'));
    }

    /**
     * Update venue configuration and operational hours.
     */
    public function updateVenue(Venue $venue, UpdateVenueRequest $request): RedirectResponse
    {
        $openTime = $request->validated('open_time');
        $closeTime = $request->validated('close_time');
        $primeStart = $request->validated('prime_start_time');
        $primeEnd = $request->validated('prime_end_time');

        $venue->update([
            'name' => $request->validated('name'),
            'address' => $request->validated('address'),
            'phone' => $request->validated('phone'),
            'email' => $request->validated('email'),
            'open_time' => strlen($openTime) === 5 ? "{$openTime}:00" : $openTime,
            'close_time' => strlen($closeTime) === 5 ? "{$closeTime}:00" : $closeTime,
            'prime_start_time' => strlen($primeStart) === 5 ? "{$primeStart}:00" : $primeStart,
            'prime_end_time' => strlen($primeEnd) === 5 ? "{$primeEnd}:00" : $primeEnd,
            'slot_lock_duration_minutes' => $request->validated('slot_lock_duration_minutes'),
        ]);

        return back()->with('status', "Data venue {$venue->name} berhasil diperbarui.");
    }

    /**
     * Display user administration table with search, role filters, and pagination.
     */
    public function users(Request $request): View
    {
        $search = $request->query('search', '');
        $roleFilter = $request->query('role', 'all');

        $query = User::withCount('bookings')->with('venue');

        if ($roleFilter !== 'all' && ! empty($roleFilter)) {
            $query->where('role', $roleFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $venues = Venue::all();

        return view('admin.users', compact('users', 'venues', 'search', 'roleFilter'));
    }

    /**
     * Toggle user status between active and suspended.
     */
    public function toggleUserStatus(User $user, Request $request): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan atau men-suspend akun Anda sendiri.']);
        }

        $newStatus = ($user->status === UserStatus::Active)
            ? UserStatus::Suspended
            : UserStatus::Active;

        $user->forceFill(['status' => $newStatus])->save();

        $statusLabel = $newStatus === UserStatus::Active ? 'diaktifkan' : 'ditangguhkan (suspend)';

        return back()->with('status', "Akun user {$user->name} berhasil {$statusLabel}.");
    }

    /**
     * Update user role and assign venue_id if role is operator.
     */
    public function updateUserRole(User $user, UpdateUserRoleRequest $request): RedirectResponse
    {
        $newRole = UserRole::from($request->validated('role'));

        // Prevent super_admin from demoting self
        if ($user->id === $request->user()->id && $newRole !== UserRole::SuperAdmin) {
            return back()->withErrors(['error' => 'Anda tidak dapat menurunkan peran (role) akun Anda sendiri.']);
        }

        $venueId = ($newRole === UserRole::Operator)
            ? (int) $request->validated('venue_id')
            : null;

        $user->forceFill([
            'role' => $newRole,
            'venue_id' => $venueId,
        ])->save();

        return back()->with('status', "Peran user {$user->name} berhasil diperbarui menjadi {$newRole->label()}.");
    }
}
