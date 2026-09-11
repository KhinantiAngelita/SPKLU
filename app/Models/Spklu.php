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

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
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