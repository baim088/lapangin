<?php

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Operator = 'operator';
    case Owner = 'owner';
    case SuperAdmin = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::Customer => 'Penyewa',
            self::Operator => 'Admin / Kasir Venue',
            self::Owner => 'Owner Venue',
            self::SuperAdmin => 'Super Admin',
        };
    }
}
