<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KandidatPrioritas extends Model
{
    use HasFactory;

    protected $table = 'kandidat_prioritas';

    protected $fillable = [
        'probabilitas_id',
        'nama_lokasi',
        'ulp_mapping_id',
        'koordinat',
        'mitra_mesin',
        'type_kw',
        'kepemilikan',
        'poin_fasilitas',
        'poin_jaringan',
        'poin_okupasi',
        'skor_prioritas',
        'demand_ulp',
        'kebutuhan_ulp',
        'jarak_real_diisi',
    ];

    protected $casts = [
        'jarak_real_diisi' => 'boolean',
    ];

    public function ulpMapping()
    {
        return $this->belongsTo(UlpMapping::class);
    }

    /**
     * Parse kolom `koordinat` (format string "lat, lng") jadi array float
     * [lat, lng]. Return null kalau kosong / formatnya tidak valid.
     */
    public function getKoordinatArrayAttribute(): ?array
    {
        if (!$this->koordinat) {
            return null;
        }

        $bagian = array_map('trim', explode(',', $this->koordinat));

        if (count($bagian) !== 2 || !is_numeric($bagian[0]) || !is_numeric($bagian[1])) {
            return null;
        }

        return [(float) $bagian[0], (float) $bagian[1]];
    }

    /**
     * Total poin (Jaringan maks 2 + Fasilitas maks 4 + Okupasi maks 4 = 10)
     * dikali 10 supaya jadi skala 0-100. Sesuai formula AH di sheet
     * 'Kandidat - Prioritas': =ROUND(AG*10,1).
     */
    public function hitungSkorPrioritas(): int
    {
        $fasilitas = $this->poin_fasilitas ?? 0;
        $jaringan = $this->poin_jaringan ?? 0;
        $okupasi = $this->poin_okupasi ?? 0;

        return (int) round(($fasilitas + $jaringan + $okupasi) * 10);
    }

    /**
     * Kategori A/B/C murni turunan (accessor) dari skor_prioritas — TIDAK
     * disimpan ke kolom terpisah, supaya tidak basi kalau poin di-update
     * tapi kategorinya lupa di-refresh.
     *
     *   skor > 80   => A, Prioritas Tinggi
     *   skor 50-80  => B, Prioritas Sedang
     *   skor < 50   => C, Prioritas Rendah
     */
    public function getKategoriAttribute(): string
    {
        if ($this->skor_prioritas > 80) {
            return 'A';
        }

        if ($this->skor_prioritas >= 50) {
            return 'B';
        }

        return 'C';
    }

    public function getKategoriWarnaAttribute(): string
    {
        return match ($this->kategori) {
            'A' => 'green',
            'B' => 'yellow',
            default => 'red',
        };
    }

    public function probabilitas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Probabilitas::class);
    }
}