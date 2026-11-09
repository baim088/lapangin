<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\CourtSlot;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpireStaleBookings
{
    /**
     * Expire pending bookings whose lock time has expired without proof of payment.
     *
     * State Machine (c):
     * Booking: expired
     * Payment: expired
     * CourtSlot: deleted
     *
     * @param  int|null  $courtId  Optional court ID to limit lazy scope
     * @param  string|null  $date  Optional date (Y-m-d) to limit lazy scope
     * @return int Number of expired bookings
     */
    public function handle(?int $courtId = null, ?string $date = null): int
    {
        $now = Carbon::now();

        $query = Booking::query()
            ->where('status', BookingStatus::Pending)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now);

        if ($courtId !== null || $date !== null) {
            $query->whereHas('items', function ($itemQuery) use ($courtId, $date) {
                if ($courtId !== null) {
                    $itemQuery->where('court_id', $courtId);
                }
                if ($date !== null) {
                    $itemQuery->whereDate('booking_date', $date);
                }
            });
        }

        $staleBookings = $query->with(['payment'])->get();

        if ($staleBookings->isEmpty()) {
            return 0;
        }

        return DB::transaction(function () use ($staleBookings) {
            $count = 0;
            foreach ($staleBookings as $booking) {
                // Update booking status to expired
                $booking->status = BookingStatus::Expired;
                $booking->save();

                // Update payment if unpaid to expired
                if ($booking->payment && $booking->payment->status === PaymentStatus::Unpaid) {
                    $booking->payment->status = PaymentStatus::Expired;
                    $booking->payment->save();
                }

                // Delete all court slots associated with this booking
                CourtSlot::where('booking_id', $booking->id)->delete();

                $count++;
            }

            return $count;
        }, 3);
    }
}
