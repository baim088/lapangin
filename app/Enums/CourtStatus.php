<?php

namespace App\Enums;

enum CourtStatus: string
{
    case Active = 'active';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Maintenance => 'Perawatan',
        };
    }
}
