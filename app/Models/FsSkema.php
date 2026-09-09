<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FsSkema extends Model
{
    protected $fillable = [
        'kandidat_id', 'skema', 'nama_lokasi', 'titik_koordinat',
        'total_rab_investasi', 'mobil_per_hari', 'layanan_listrik',
        'transaksi_kwh_per_mobil', 'fasilitas', 'poin_fasilitas',
        'kesiapan_jaringan', 'poin_kesiapan_jaringan', 'okupansi',
        'poin_okupansi', 'total_poin', 'status_kelayakan',
        'narasi_analisis', 'created_by',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'okupansi' => 'array',
        'total_rab_investasi' => 'decimal:2',
        'transaksi_kwh_per_mobil' => 'decimal:2',
    ];

    public function kandidat()
    {
        return $this->belongsTo(KandidatPrioritas::class, 'kandidat_id');
    }

    public function pengajuan()
    {
        return $this->hasOne(Pengajuan::class);
    }
}