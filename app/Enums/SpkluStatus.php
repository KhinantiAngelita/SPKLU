<?php

namespace App\Enums;

enum SpkluStatus: string
{
    case MenungguValidasi = 'menunggu_validasi';
    case Aktif = 'aktif';
    case Nonaktif = 'nonaktif';

    public function label(): string
    {
        return match ($this) {
            self::MenungguValidasi => 'Menunggu Validasi',
            self::Aktif => 'Aktif',
            self::Nonaktif => 'Nonaktif',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::MenungguValidasi => 'amber',
            self::Aktif => 'green',
            self::Nonaktif => 'gray',
        };
    }
}