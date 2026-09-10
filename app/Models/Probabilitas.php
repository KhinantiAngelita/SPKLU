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
        'spklu_id',
        'divalidasi_pada',
        'divalidasi_oleh',
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
        'divalidasi_pada' => 'datetime',
    ];

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

    public function spklu()
    {
        return $this->belongsTo(Spklu::class);
    }

    public function divalidasiOleh()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    public function riwayatPerTahap(): array
    {
        $grouped = $this->riwayatTahapan->groupBy('tahap');

        $result = [];
        foreach (array_keys(self::TAHAPAN) as $key) {
            $result[$key] = ($grouped->get($key) ?? collect())->sortByDesc('tanggal')->values();
        }

        return $result;
    }

    public function badgePerTahap(): array
    {
        $badges = [];

        foreach ($this->riwayatPerTahap() as $tahap => $riwayat) {
            if ($riwayat->isEmpty()) {
                $badges[$tahap] = ['label' => 'Belum ada', 'warna' => 'abu', 'jumlah' => 0];
                continue;
            }

            $terakhir = $riwayat->first();
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

    public function tahapSaatIni(): string
    {
        $badges = $this->badgePerTahap();

        foreach (self::TAHAPAN as $key => $label) {
            if (($badges[$key]['warna'] ?? 'abu') !== 'hijau') {
                return $label;
            }
        }

        return 'Integrasi';
    }

    public function statusKanban(): string
    {
        $badges = $this->badgePerTahap();

        if (($badges['integrasi']['warna'] ?? 'abu') === 'hijau') {
            return 'selesai_integrasi';
        }

        $adaYangSelesai = collect($badges)->contains(fn ($b) => $b['warna'] === 'hijau');

        return $adaYangSelesai ? 'on_progress' : 'belum_mulai';
    }

    public function tanggalUpdateTerakhir(): \Illuminate\Support\Carbon
    {
        $terbaru = $this->riwayatTahapan->sortByDesc('tanggal')->first();

        return $terbaru ? \Illuminate\Support\Carbon::parse($terbaru->tanggal) : $this->created_at;
    }

    public function sudahDivalidasi(): bool
    {
        return $this->spklu_id !== null;
    }

    /** Total KW hasil jumlah semua kebutuhan mesin — dipakai sebagai nilai awal (bisa diedit) di modal validasi. */
    public function estimasiTotalKw(): float
    {
        return (22 * ($this->kebutuhan_22kw ?? 0))
            + (30 * ($this->kebutuhan_30kw ?? 0))
            + (50 * ($this->kebutuhan_50kw ?? 0))
            + (60 * ($this->kebutuhan_60kw ?? 0))
            + (120 * ($this->kebutuhan_120kw ?? 0))
            + (180 * ($this->kebutuhan_180kw ?? 0));
    }

    /** Total unit mesin (dipakai sebagai estimasi jumlah nozzle awal, bisa diedit). */
    public function estimasiNozzle(): int
    {
        return (int) (($this->kebutuhan_22kw ?? 0) + ($this->kebutuhan_30kw ?? 0) + ($this->kebutuhan_50kw ?? 0)
            + ($this->kebutuhan_60kw ?? 0) + ($this->kebutuhan_120kw ?? 0) + ($this->kebutuhan_180kw ?? 0));
    }
}