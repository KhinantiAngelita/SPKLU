<?php

namespace App\Enums;

enum UserStatus: string
{
    case Pending = 'pending';   // sudah diundang, belum aktivasi
    case Active = 'active';
    case Nonaktif = 'nonaktif';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Aktivasi',
            self::Active => 'Aktif',
            self::Nonaktif => 'Nonaktif',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Active => 'green',
            self::Nonaktif => 'gray',
        };
    }
}