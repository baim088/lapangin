<?php

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Operator = 'operator';
    case SuperAdmin = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::Customer => 'Penyewa',
            self::Operator => 'Operator',
            self::SuperAdmin => 'Super Admin',
        };
    }
}
