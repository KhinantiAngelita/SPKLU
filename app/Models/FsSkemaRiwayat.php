<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FsSkemaRiwayat extends Model
{
    protected $table = 'fs_skema_riwayats';

    protected $fillable = [
        'fs_skema_id',
        'poin_fasilitas',
        'poin_kesiapan_jaringan',
        'poin_okupansi',
        'total_poin',
        'status_kelayakan',
        'narasi_analisis',
        'dicatat_oleh',
    ];

    public function fsSkema(): BelongsTo
    {
        return $this->belongsTo(FsSkema::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
