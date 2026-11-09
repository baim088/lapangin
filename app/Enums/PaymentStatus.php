<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case PendingVerification = 'pending_verification';
    case Paid = 'paid';
    case Expired = 'expired';
    case Rejected = 'rejected';
    case RefundPending = 'refund_pending';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Belum Dibayar',
            self::PendingVerification => 'Menunggu Verifikasi',
            self::Paid => 'Lunas',
            self::Expired => 'Kadaluarsa',
            self::Rejected => 'Ditolak',
            self::RefundPending => 'Menunggu Pengembalian Dana',
            self::Refunded => 'Dana Dikembalikan',
        };
    }
}
