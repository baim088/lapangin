<?php

namespace App\Enums;

enum SportCategory: string
{
    case Futsal = 'futsal';
    case Badminton = 'badminton';
    case MiniSoccer = 'minisoccer';
    case Padel = 'padel';

    public function label(): string
    {
        return match ($this) {
            self::Futsal => 'Futsal',
            self::Badminton => 'Badminton',
            self::MiniSoccer => 'Mini Soccer',
            self::Padel => 'Padel Court',
        };
    }
}
