<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlpMapping extends Model
{
    protected $fillable = ['nama_singkat', 'nama_penuh', 'jarak_ideal_km', 'kategori_area'];

    /**
     * Pemetaan resmi Kode ULP berdasarkan data PLN UID Jawa Barat:
     * 53811 - ULP Cipayung
     * 53821 - ULP Bogor Timur
     * 53825 - ULP Pakuan
     * 53831 - ULP Bogor Kota
     * 53841 - ULP Bogor Barat
     * 53851 - ULP Leuwiliang
     * 53853 - ULP Jasinga
     */
    public const KODE_UNIT_BY_ID = [
        1 => '53831', // Bogor Kota
        2 => '53841', // Bogor Barat
        3 => '53821', // Bogor Timur
        4 => '53825', // Prima Pakuan (TT/TM)
        5 => '53811', // Cipayung
        6 => '53851', // Leuwiliang
        7 => '53853', // Jasinga
    ];

    public static function getKodeUnitByNama(string $nama): ?string
    {
        $normalized = strtolower(trim($nama));
        if (str_contains($normalized, 'cipayung')) {
            return '53811';
        }
        if (str_contains($normalized, 'timur')) {
            return '53821';
        }
        if (str_contains($normalized, 'pakuan')) {
            return '53825';
        }
        if (str_contains($normalized, 'kota')) {
            return '53831';
        }
        if (str_contains($normalized, 'barat')) {
            return '53841';
        }
        if (str_contains($normalized, 'leuwiliang')) {
            return '53851';
        }
        if (str_contains($normalized, 'jasinga')) {
            return '53853';
        }

        return null;
    }

    public function getKodeUnitAttribute(): ?string
    {
        return self::getKodeUnitByNama($this->nama_penuh ?? $this->nama_singkat ?? '')
            ?? (self::KODE_UNIT_BY_ID[$this->id] ?? null);
    }

    public function spklus()
    {
        return $this->hasMany(Spklu::class);
    }
}
