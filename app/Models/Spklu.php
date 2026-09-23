<?php

namespace App\Models;

use App\Enums\SpkluStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spklu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_spklu', 'id_spklu_sumber', 'kode_unit', 'nama', 'ulp_mapping_id', 'type', 'kw', 'kw_detail', 'nozzle',
        'kepemilikan', 'skema', 'tanggal_aktif', 'latitude', 'longitude', 'status', 'sumber',
        'pengajuan_id', 'validated_by', 'validated_at',
    ];

    protected $casts = [
        'status' => SpkluStatus::class,
        'validated_at' => 'datetime',
        'tanggal_aktif' => 'date',
    ];

    public function ulp()
    {
        return $this->belongsTo(UlpMapping::class, 'ulp_mapping_id');
    }

    /**
     * Accessor: jika kolom kode_unit di database kosong/null,
     * otomatis panggil Kode Unit resmi berdasarkan ULP-nya.
     */
    public function getKodeUnitAttribute($value): ?string
    {
        if (! empty($value) && $value !== '—') {
            return $value;
        }

        if ($this->ulp_mapping_id) {
            $ulp = $this->relationLoaded('ulp') ? $this->ulp : $this->ulp()->first();

            return $ulp?->kode_unit ?? (UlpMapping::KODE_UNIT_BY_ID[$this->ulp_mapping_id] ?? null);
        }

        return null;
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Mengambil daya kW efektif / nominal tertinggi.
     * Jika kolom kw numerik null (karena dari import formatnya '22,4x120', '2x120', dsb.),
     * ekstrak angka daya tertinggi dari kw_detail.
     */
    public function getEffectiveKw(): float
    {
        if ($this->kw !== null && (float) $this->kw > 0) {
            return (float) $this->kw;
        }

        if (! empty($this->kw_detail)) {
            preg_match_all('/(?:(\d+(?:\.\d+)?)\s*[xX]\s*)?(\d+(?:\.\d+)?)/', $this->kw_detail, $matches, PREG_SET_ORDER);
            $numbers = [];
            foreach ($matches as $m) {
                if (! empty($m[2])) {
                    $numbers[] = (float) $m[2];
                }
            }
            if (! empty($numbers)) {
                return (float) max($numbers);
            }
        }

        return 0.0;
    }

    public function getEffectiveKwAttribute(): float
    {
        return $this->getEffectiveKw();
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function scopeMenungguValidasi($query)
    {
        return $query->where('status', SpkluStatus::MenungguValidasi);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', SpkluStatus::Aktif);
    }

    /** Rata-rata transaksi/bulan — dipakai di tabel Master SPKLU */
    public function rataRataTransaksiPerBulan(): float
    {
        $result = $this->transaksis()
            ->selectRaw('COUNT(*) as total, DATEDIFF(MAX(tanggal), MIN(tanggal)) as rentang_hari')
            ->first();

        if (! $result || ! $result->total) {
            return 0;
        }

        $bulan = max($result->rentang_hari / 30, 1);

        return round($result->total / $bulan, 1);
    }
}
