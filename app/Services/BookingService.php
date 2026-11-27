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
     * State Machine:
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
        array $customerData,
        string $paymentType = 'full',
        int $dpPercentage = 50
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

        $isDp = ($paymentType === 'dp');
        $dpPct = $isDp ? max(30, min(50, $dpPercentage)) : 50;
        $dpAmount = $isDp ? (int) round($totalAmount * ($dpPct / 100)) : 0;
        $initialPayAmount = $isDp ? $dpAmount : $totalAmount;

        $expiresAt = $now->copy()->addMinutes($venue->slot_lock_duration_minutes ?? 10);

        return DB::transaction(function () use (
            $user,
            $court,
            $bookingDate,
            $normalizedSlots,
            $totalHours,
            $subtotal,
            $totalAmount,
            $paymentType,
            $dpPct,
            $dpAmount,
            $initialPayAmount,
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
                'payment_type' => $paymentType,
                'dp_percentage' => $dpPct,
                'dp_amount' => $dpAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
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
                'payment_type' => $paymentType === 'dp' ? 'dp' : 'full',
                'status' => PaymentStatus::Unpaid,
                'amount' => $initialPayAmount,
            ]);

            return $booking;
        }, 3);
    }

    /**
     * Upload proof of payment on time.
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
     */
    public function cancelPendingByCustomer(Booking $booking, User $customer): void
    {
        if (! in_array($booking->status, [BookingStatus::Pending, BookingStatus::PendingPayment], true)) {
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
     * Operator confirms initial payment (either Full Payment or Down Payment).
     */
    public function confirmByOperator(Booking $booking, User $operator): void
    {
        DB::transaction(function () use ($booking, $operator) {
            /** @var Payment $payment */
            $payment = $booking->payment ?? Payment::where('booking_id', $booking->id)->latest()->firstOrFail();
            $payment->status = PaymentStatus::Paid;
            $payment->verified_by = $operator->id;
            $payment->verified_at = Carbon::now();
            $payment->save();

            if ($booking->payment_type === 'dp') {
                $booking->status = BookingStatus::DpPaid;
                $booking->paid_amount = $booking->dp_amount;
                $booking->remaining_amount = max(0, $booking->total_amount - $booking->dp_amount);
            } else {
                $booking->status = BookingStatus::PaidInFull;
                $booking->paid_amount = $booking->total_amount;
                $booking->remaining_amount = 0;
            }
            $booking->expires_at = null;
            $booking->save();

            // Slot remains confirmed with locked_until = NULL
            CourtSlot::where('booking_id', $booking->id)->update([
                'locked_until' => null,
            ]);
        }, 3);
    }

    /**
     * Operator / Cashier processes on-site settlement for dp_paid booking.
     */
    public function settlePaymentByOperator(
        Booking $booking,
        User $cashier,
        PaymentMethod|string $settlementMethod,
        ?string $notes = null
    ): Payment {
        if ($booking->remaining_amount <= 0 && $booking->status !== BookingStatus::DpPaid) {
            throw ValidationException::withMessages([
                'booking' => 'Booking ini sudah lunas atau tidak memerlukan pelunasan.',
            ]);
        }

        $methodEnum = is_string($settlementMethod)
            ? (PaymentMethod::tryFrom($settlementMethod) ?? PaymentMethod::Cash)
            : $settlementMethod;

        return DB::transaction(function () use ($booking, $cashier, $methodEnum, $notes) {
            $settleAmount = $booking->remaining_amount;

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $methodEnum,
                'payment_type' => 'settlement',
                'status' => PaymentStatus::Paid,
                'amount' => $settleAmount,
                'verified_by' => $cashier->id,
                'verified_at' => Carbon::now(),
                'cashier_id' => $cashier->id,
                'notes' => $notes,
            ]);

            $booking->paid_amount += $settleAmount;
            $booking->remaining_amount = 0;
            $booking->status = BookingStatus::PaidInFull;
            $booking->settlement_method = $methodEnum->value;
            $booking->settled_at = Carbon::now();
            $booking->settled_by = $cashier->id;
            $booking->save();

            return $payment;
        }, 3);
    }

    /**
     * Operator rejects payment (reason required).
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
