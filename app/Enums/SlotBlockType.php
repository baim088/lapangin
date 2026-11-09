<?php

namespace App\Enums;

enum SlotBlockType: string
{
    case Booking = 'booking';
    case Maintenance = 'maintenance';
    case Event = 'event';

    public function label(): string
    {
        return match ($this) {
            self::Booking => 'Reservasi Penyewa',
            self::Maintenance => 'Perawatan Lapangan',
            self::Event => 'Kegiatan / Turnamen',
        };
    }
}
