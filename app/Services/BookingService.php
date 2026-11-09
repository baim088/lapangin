<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\CourtStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SlotBlockType;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a pending booking with slot locks.
     *
     * State Machine (a):
     * Booking: pending (expires_at = now + slot_lock_duration_minutes)
     * Payment: unpaid
     * CourtSlot: created with locked_until = expires_at
     *
     * @param  array<int, string>  $startTimes  e.g. ['09:00', '10:00']
     * @param  array{name: string, phone: string, email: string, notes?: string|null}  $customerData
     */
    public function createPending(
        User $user,
        Court $court,
        string $bookingDate,
        array $startTimes,
        PaymentMethod $paymentMethod,
        array $customerData
    ): Booking {
        if ($court->status !== CourtStatus::Active) {
            throw ValidationException::withMessages([
                'court' => 'Lapangan ini sedang tidak aktif atau dalam perawatan dan tidak dapat dipesan.',
            ]);
        }

        if (empty($startTimes)) {
            throw ValidationException::withMessages([
                'slots' => 'Pilih minimal satu jam/slot untuk melakukan booking.',
            ]);
        }

        $court->loadMissing('venue');
        $venue = $court->venue;
        $now = Carbon::now();

        // Normalize & sort start times
        $normalizedSlots = [];
        $primeStart = Carbon::createFromTimeString($venue->prime_start_time ?? '17:00:00');
        $primeEnd = Carbon::createFromTimeString($venue->prime_end_time ?? '23:00:00');

        foreach ($startTimes as $time) {
            $carbonTime = Carbon::createFromTimeString($time);
            $startFormatted = $carbonTime->format('H:i:00');
            $endFormatted = $carbonTime->copy()->addHour()->format('H:i:00');
            $slotDateTime = Carbon::parse("{$bookingDate} {$startFormatted}");

            if ($slotDateTime->lte($now)) {
                throw ValidationException::withMessages([
                    'slots' => 'Slot waktu yang dipilih sudah lewat dan tidak dapat dipesan.',
                ]);
            }

            $isPrime = $carbonTime->gte($primeStart) && $carbonTime->lt($primeEnd);
            $price = $isPrime ? (int) $court->price_prime : (int) $court->price_regular;

            $normalizedSlots[$startFormatted] = [
                'start_time' => $startFormatted,
                'end_time' => $endFormatted,
                'is_prime' => $isPrime,
                'price' => $price,
            ];
        }

        ksort($normalizedSlots);

        $totalHours = count($normalizedSlots);
        $subtotal = array_sum(array_column($normalizedSlots, 'price'));
        $totalAmount = $subtotal;

        $expiresAt = $now->copy()->addMinutes($venue->slot_lock_duration_minutes ?? 10);

        return DB::transaction(function () use (
            $user,
            $court,
            $bookingDate,
            $normalizedSlots,
            $totalHours,
            $subtotal,
            $totalAmount,
            $expiresAt,
            $paymentMethod,
            $customerData,
            $now
        ) {
            // Check availability without SELECT FOR UPDATE
            $slotTimes = array_keys($normalizedSlots);
            $occupied = CourtSlot::query()
                ->where('court_id', $court->id)
                ->whereDate('booking_date', $bookingDate)
                ->whereIn('start_time', $slotTimes)
                ->where(function ($q) use ($now) {
                    $q->whereNull('locked_until')
                        ->orWhere('locked_until', '>', $now);
                })
                ->exists();

            if ($occupied) {
                throw ValidationException::withMessages([
                    'slots' => 'Salah satu slot waktu yang Anda pilih sudah terisi. Silakan pilih slot lain.',
                ]);
            }

            $bookingCode = $this->generateBookingCode();
            $checkinToken = Str::random(64);

            $booking = Booking::create([
                'venue_id' => $court->venue_id,
                'user_id' => $user->id,
                'booking_code' => $bookingCode,
                'customer_name' => $customerData['name'] ?? $user->name,
                'customer_phone' => $customerData['phone'] ?? $user->phone,
                'customer_email' => $customerData['email'] ?? $user->email,
                'notes' => $customerData['notes'] ?? null,
                'total_hours' => $totalHours,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'status' => BookingStatus::Pending,
                'expires_at' => $expiresAt,
                'checkin_token' => $checkinToken,
            ]);

            try {
                foreach ($normalizedSlots as $slot) {
                    BookingItem::create([
                        'booking_id' => $booking->id,
                        'court_id' => $court->id,
                        'booking_date' => $bookingDate,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'is_prime' => $slot['is_prime'],
                        'price_per_hour' => $slot['price'],
                    ]);

                    CourtSlot::create([
                        'court_id' => $court->id,
                        'booking_id' => $booking->id,
                        'booking_date' => $bookingDate,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'block_type' => SlotBlockType::Booking,
                        'locked_until' => $expiresAt,
                    ]);
                }
            } catch (UniqueConstraintViolationException $e) {
                throw ValidationException::withMessages([
                    'slots' => 'Slot waktu yang Anda pilih baru saja diambil oleh penyewa lain. Silakan pilih slot lain.',
                ]);
            }

            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $paymentMethod,
                'status' => PaymentStatus::Unpaid,
                'amount' => $totalAmount,
            ]);

            return $booking;
        }, 3);
    }

    /**
     * Upload proof of payment on time.
     *
     * State Machine (b) & (h):
     * On time: Payment: pending_verification, Booking: expires_at = NULL, Slot: locked_until = NULL
     * Expired: Rejected with instruction to contact admin.
     */
    public function uploadProof(Booking $booking, UploadedFile $file): Payment
    {
        $now = Carbon::now();

        if ($booking->status === BookingStatus::Expired || ($booking->expires_at && $booking->expires_at->lte($now))) {
            throw ValidationException::withMessages([
                'proof' => 'Batas waktu pembayaran untuk booking ini telah kedaluwarsa. Silakan hubungi admin kami untuk bantuan.',
            ]);
        }

        $path = $file->store('payment-proofs', 'local');

        return DB::transaction(function () use ($booking, $path, $now) {
            /** @var Payment $payment */
            $payment = $booking->payment ?? Payment::where('booking_id', $booking->id)->latest()->firstOrFail();

            $payment->proof_path = $path;
            $payment->proof_uploaded_at = $now;
            $payment->status = PaymentStatus::PendingVerification;
            $payment->save();

            // Freeze lock
            $booking->expires_at = null;
            $booking->save();

            CourtSlot::where('booking_id', $booking->id)->update([
                'locked_until' => null,
            ]);

            return $payment;
        }, 3);
    }

    /**
     * Customer cancels a pending booking.
     *
     * State Machine (f):
     * Booking: cancelled (cancelled_at), Payment: unpaid, Slot: DELETED.
     */
    public function cancelPendingByCustomer(Booking $booking, User $customer): void
    {
        if ($booking->status !== BookingStatus::Pending) {
            throw ValidationException::withMessages([
                'booking' => 'Hanya booking yang berstatus pending yang dapat dibatalkan oleh penyewa.',
            ]);
        }

        DB::transaction(function () use ($booking) {
            $booking->status = BookingStatus::Cancelled;
            $booking->cancelled_at = Carbon::now();
            $booking->cancelled_reason = 'Dibatalkan oleh penyewa';
            $booking->save();

            CourtSlot::where('booking_id', $booking->id)->delete();
        }, 3);
    }

    /**
     * Operator confirms payment.
     *
     * State Machine (d):
     * Booking: confirmed, Payment: paid (verified_by, verified_at), Slot: locked_until stays NULL.
     */
    public function confirmByOperator(Booking $booking, User $operator): void
    {
        DB::transaction(function () use ($booking, $operator) {
            $booking->status = BookingStatus::Confirmed;
            $booking->save();

            /** @var Payment $payment */
            $payment = $booking->payment ?? Payment::where('booking_id', $booking->id)->latest()->firstOrFail();
            $payment->status = PaymentStatus::Paid;
            $payment->verified_by = $operator->id;
            $payment->verified_at = Carbon::now();
            $payment->save();

            // Slot remains with locked_until = NULL
            CourtSlot::where('booking_id', $booking->id)->update([
                'locked_until' => null,
            ]);
        }, 3);
    }

    /**
     * Operator rejects payment (reason required).
     *
     * State Machine (e):
     * Booking: cancelled (cancelled_reason), Payment: rejected, Slot: DELETED.
     */
    public function rejectByOperator(Booking $booking, User $operator, string $reason): void
    {
        if (trim($reason) === '') {
            throw ValidationException::withMessages([
                'reason' => 'Alasan penolakan pembayaran wajib diisi.',
            ]);
        }

        DB::transaction(function () use ($booking, $reason) {
            $booking->status = BookingStatus::Cancelled;
            $booking->cancelled_at = Carbon::now();
            $booking->cancelled_reason = $reason;
            $booking->save();

            /** @var Payment $payment */
            $payment = $booking->payment ?? Payment::where('booking_id', $booking->id)->latest()->firstOrFail();
            $payment->status = PaymentStatus::Rejected;
            $payment->save();

            CourtSlot::where('booking_id', $booking->id)->delete();
        }, 3);
    }

    /**
     * Operator cancels confirmed booking.
     *
     * State Machine (g):
     * Booking: cancelled, Payment: refund_pending, Slot: DELETED.
     */
    public function cancelConfirmedByOperator(Booking $booking, User $operator, string $reason): void
    {
        if (trim($reason) === '') {
            throw ValidationException::withMessages([
                'reason' => 'Alasan pembatalan wajib diisi.',
            ]);
        }

        DB::transaction(function () use ($booking, $reason) {
            $booking->status = BookingStatus::Cancelled;
            $booking->cancelled_at = Carbon::now();
            $booking->cancelled_reason = $reason;
            $booking->save();

            /** @var Payment $payment */
            $payment = $booking->payment ?? Payment::where('booking_id', $booking->id)->latest()->firstOrFail();
            $payment->status = PaymentStatus::RefundPending;
            $payment->save();

            CourtSlot::where('booking_id', $booking->id)->delete();
        }, 3);
    }

    /**
     * Generate unique booking code format: LPN-{tahun}-{4 digit acak}.
     */
    protected function generateBookingCode(): string
    {
        $year = Carbon::now()->year;

        for ($i = 0; $i < 5; $i++) {
            $code = sprintf('LPN-%d-%04d', $year, random_int(1000, 9999));
            if (! Booking::where('booking_code', $code)->exists()) {
                return $code;
            }
        }

        return sprintf('LPN-%d-%06d', $year, random_int(100000, 999999));
    }
}
