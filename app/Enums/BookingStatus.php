<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Done = 'done';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Pembayaran',
            self::Confirmed => 'Terkonfirmasi',
            self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
            self::Expired => 'Kadaluarsa',
        };
    }
}
