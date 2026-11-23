<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Qris = 'qris';
    case EWallet = 'e_wallet';
    case VirtualAccount = 'virtual_account';
    case ManualTransfer = 'manual_transfer';
    case Cash = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::Qris => 'QRIS',
            self::EWallet => 'GoPay / OVO / Dana',
            self::VirtualAccount => 'Virtual Account Bank',
            self::ManualTransfer => 'Transfer Bank Manual',
            self::Cash => 'Tunai / Cash (Di Tempat)',
        };
    }
}
