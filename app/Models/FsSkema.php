<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FsSkema extends Model
{
    protected $fillable = [
        'kandidat_id', 'skema', 'nama_lokasi', 'titik_koordinat',
        'total_rab_investasi', 'rab_mitra_mesin', 'rab_mitra_lahan', 'sharing_provit_mitra_lahan',
        'mobil_per_hari', 'layanan_listrik', 'transaksi_kwh_per_mobil',
        'fasilitas', 'poin_fasilitas', 'kesiapan_jaringan', 'poin_kesiapan_jaringan',
        'okupansi', 'poin_okupansi', 'total_poin', 'status_kelayakan',
        'narasi_analisis', 'created_by',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'okupansi' => 'array',
        'total_rab_investasi' => 'decimal:2',
        'rab_mitra_mesin' => 'decimal:2',
        'rab_mitra_lahan' => 'decimal:2',
        'sharing_provit_mitra_lahan' => 'decimal:4',
        'transaksi_kwh_per_mobil' => 'decimal:2',
    ];

    public function kandidat()
    {
        return $this->belongsTo(KandidatPrioritas::class, 'kandidat_id');
    }

    public function isSkema3(): bool
    {
        return $this->skema === 'skema_3';
    }

    /** Titik koordinat dipecah jadi [lat, lng] float — dipakai buat hitung jarak. */
    public function koordinat(): ?array
    {
        if (! $this->titik_koordinat) {
            return null;
        }

        $parts = array_map('trim', explode(',', $this->titik_koordinat));

        if (count($parts) !== 2 || ! is_numeric($parts[0]) || ! is_numeric($parts[1])) {
            return null;
        }

        return [(float) $parts[0], (float) $parts[1]];
    }
}