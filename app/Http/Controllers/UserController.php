<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
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
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::Done])
            ->count();
        $cancelledBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Cancelled, BookingStatus::Expired])
            ->count();
        $totalSpent = (int) Booking::where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::Done])
            ->sum('total_amount');

        // 2. Bookings grouped for tabs
        $upcomingBookings = Booking::with(['items.court', 'venue', 'payment'])
            ->where('user_id', $user->id)
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->latest()
            ->get();

        $historyBookings = Booking::with(['items.court', 'venue', 'payment'])
            ->where('user_id', $user->id)
            ->where('status', BookingStatus::Done)
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
}
