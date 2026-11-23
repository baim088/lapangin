<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case PendingPayment = 'pending_payment';
    case DpPaid = 'dp_paid';
    case Confirmed = 'confirmed';
    case PaidInFull = 'paid_in_full';
    case Done = 'done';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending, self::PendingPayment => 'Menunggu Pembayaran',
            self::DpPaid => 'DP Terbayar (Sisa di Lokasi)',
            self::Confirmed, self::PaidInFull => 'Lunas & Terkonfirmasi',
            self::Done, self::Completed => 'Selesai Bermain',
            self::Cancelled => 'Dibatalkan',
            self::Expired => 'Kedaluwarsa',
        };
    }
}
