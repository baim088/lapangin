<?php

namespace App\Policies;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine whether the user can view the booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOwner()) {
            return $user->ownedVenues()->where('venues.id', $booking->venue_id)->exists();
        }

        if ($user->isOperator()) {
            return $user->venue_id === $booking->venue_id;
        }

        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOwner()) {
            return $user->ownedVenues()->where('venues.id', $booking->venue_id)->exists();
        }

        if ($user->isOperator()) {
            return $user->venue_id === $booking->venue_id;
        }

        return $user->id === $booking->user_id && in_array($booking->status, [BookingStatus::Pending, BookingStatus::PendingPayment], true);
    }

    /**
     * Determine whether the user can update or settle the booking.
     */
    public function update(User $user, Booking $booking): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOwner()) {
            return $user->ownedVenues()->where('venues.id', $booking->venue_id)->exists();
        }

        if ($user->isOperator()) {
            return $user->venue_id === $booking->venue_id;
        }

        return $user->id === $booking->user_id;
    }
}
