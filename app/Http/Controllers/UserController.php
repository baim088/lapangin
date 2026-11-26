<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Court;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display user dashboard with real statistics and bookings.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        // 1. Statistics calculated from database queries
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $successfulBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', [
                BookingStatus::Confirmed,
                BookingStatus::PaidInFull,
                BookingStatus::DpPaid,
                BookingStatus::Done,
                BookingStatus::Completed,
            ])
            ->count();
        $cancelledBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->count();
        $totalSpent = (int) Booking::where('user_id', $user->id)
            ->whereIn('status', [
                BookingStatus::Confirmed,
                BookingStatus::PaidInFull,
                BookingStatus::DpPaid,
                BookingStatus::Done,
                BookingStatus::Completed,
            ])
            ->sum('paid_amount');

        // 2. Bookings grouped for tabs
        $upcomingBookings = Booking::with(['items.court', 'venue', 'payment'])
            ->where('user_id', $user->id)
            ->whereIn('status', [
                BookingStatus::Pending,
                BookingStatus::PendingPayment,
                BookingStatus::DpPaid,
                BookingStatus::Confirmed,
                BookingStatus::PaidInFull,
            ])
            ->latest()
            ->get();

        $historyBookings = Booking::with(['items.court', 'venue', 'payment'])
            ->where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Done, BookingStatus::Completed])
            ->latest()
            ->get();

        $cancelledList = Booking::with(['items.court', 'venue', 'payment'])
            ->where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->latest()
            ->get();

        return view('user.dashboard', compact(
            'user',
            'totalBookings',
            'successfulBookings',
            'cancelledBookings',
            'totalSpent',
            'upcomingBookings',
            'historyBookings',
            'cancelledList'
        ));
    }

    /**
     * Display booking detail and e-ticket.
     */
    public function bookingDetail(string $id, Request $request): View
    {
        $booking = Booking::with(['items.court', 'venue', 'payment'])
            ->where(function ($query) use ($id) {
                $query->where('id', $id)
                    ->orWhere('booking_code', $id);
            })
            ->firstOrFail();

        $this->authorize('view', $booking);

        return view('user.booking-detail', compact('booking', 'id'));
    }

    /**
     * Display internal court catalog inside the authenticated user dashboard.
     */
    public function cariLapangan(Request $request): View
    {
        $user = $request->user();
        $category = $request->query('category', 'all');
        $region = $request->query('region', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Court::with('venue')->orderByDesc('id');

        if ($category !== 'all') {
            $mappedCategory = $category === 'minisoccer' ? 'mini_soccer' : $category;
            $query->where('sport_category', $mappedCategory);
        }

        if ($region !== 'all') {
            $query->whereHas('venue', function ($q) use ($region) {
                $q->where('address', 'like', '%'.$region.'%');
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('surface_type', 'like', '%'.$search.'%')
                    ->orWhereHas('venue', function ($vq) use ($search) {
                        $vq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('address', 'like', '%'.$search.'%');
                    });
            });
        }

        $courts = $query->paginate(15)->withQueryString();

        return view('user.cari-lapangan', compact('user', 'courts', 'category', 'region', 'search'));
    }
}
