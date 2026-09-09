<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Probabilitas extends Model
{
    use HasFactory;

    protected $table = 'probabilitas';

    protected $fillable = [
        'lokasi',
        'tikor_lat',
        'tikor_lng',
        'ulp',
        'skema',
        'kebutuhan_22kw',
        'kebutuhan_30kw',
        'kebutuhan_50kw',
        'kebutuhan_60kw',
        'kebutuhan_120kw',
        'kebutuhan_180kw',
        'mitra_mesin',
        'poin_perluasan_jaringan',
        'fasilitas_ruang_tunggu',
        'fasilitas_parkir',
        'fasilitas_toilet',
        'fasilitas_kafe',
        'okupansi_perumahan',
        'okupansi_pintu_tol',
        'okupansi_pusat_keramaian',
        'okupansi_ruas_jalan',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'fasilitas_ruang_tunggu' => 'boolean',
        'fasilitas_parkir' => 'boolean',
        'fasilitas_toilet' => 'boolean',
        'fasilitas_kafe' => 'boolean',
        'okupansi_perumahan' => 'boolean',
        'okupansi_pintu_tol' => 'boolean',
        'okupansi_pusat_keramaian' => 'boolean',
        'okupansi_ruas_jalan' => 'boolean',
        'persentase_progres' => 'decimal:2',
    ];

    /**
     * Urutan resmi 11 tahap — dipakai untuk generate baris kosong saat
     * lokasi baru dibuat, dan untuk urutan kolom di grid & modal.
     * Key = value enum di DB, value = label tampilan.
     */
    public const TAHAPAN = [
        'probing' => 'Probing',
        'survey_nps' => 'Survey NPS',
        'surat_masuk' => 'Surat Masuk',
        'survey_ulp' => 'Survey ULP',
        'rab' => 'RAB',
        'mitra_mesin' => 'Mitra Mesin',
        'kpp_final' => 'KPP (final)',
        'pks' => 'PKS',
        'bayar_bp' => 'Bayar BP',
        'pembangunan' => 'Pembangunan',
        'integrasi' => 'Integrasi',
    ];

    public function riwayatTahapan(): HasMany
    {
        return $this->hasMany(TahapanProbing::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Kunjungan terakhir per tahap, dikelompokkan — dipakai untuk render
     * badge grid tanpa query N+1 (eager load riwayatTahapan lalu panggil
     * ini di memory).
     *
     * @return array<string, \Illuminate\Support\Collection>
     */
    public function riwayatPerTahap(): array
    {
        $grouped = $this->riwayatTahapan->groupBy('tahap');

        $result = [];
        foreach (array_keys(self::TAHAPAN) as $key) {
            $result[$key] = ($grouped->get($key) ?? collect())->sortByDesc('tanggal')->values();
        }

        return $result;
    }

    /**
     * Data badge siap-render per tahap: label, warna, jumlah kunjungan.
     * Dipakai langsung di Blade grid.
     */
    public function badgePerTahap(): array
    {
        $badges = [];

        foreach ($this->riwayatPerTahap() as $tahap => $riwayat) {
            if ($riwayat->isEmpty()) {
                $badges[$tahap] = ['label' => 'Belum ada', 'warna' => 'abu', 'jumlah' => 0];
                continue;
            }

            $terakhir = $riwayat->first(); // sudah diurutkan terbaru dulu
            $jumlah = $riwayat->count();

            $badges[$tahap] = match ($terakhir->hasil) {
                'berhasil' => ['label' => "✓ {$jumlah}x", 'warna' => 'hijau', 'jumlah' => $jumlah],
                'perlu_kunjungan_ulang' => ['label' => "⟳ {$jumlah}x", 'warna' => 'kuning', 'jumlah' => $jumlah],
                'gagal' => ['label' => "✗ {$jumlah}x", 'warna' => 'merah', 'jumlah' => $jumlah],
                default => ['label' => 'Belum ada', 'warna' => 'abu', 'jumlah' => 0],
            };
        }

        return $badges;
    }
}