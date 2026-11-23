<?php

namespace App\Enums;

enum SportCategory: string
{
    case Futsal = 'futsal';
    case Badminton = 'badminton';
    case MiniSoccer = 'minisoccer';
    case Basketball = 'basketball';
    case Volleyball = 'volleyball';
    case Padel = 'padel';

    public function label(): string
    {
        return match ($this) {
            self::Futsal => 'Futsal',
            self::Badminton => 'Badminton',
            self::MiniSoccer => 'Mini Soccer',
            self::Basketball => 'Bola Basket',
            self::Volleyball => 'Bola Voli',
            self::Padel => 'Padel Court',
        };
    }
}
