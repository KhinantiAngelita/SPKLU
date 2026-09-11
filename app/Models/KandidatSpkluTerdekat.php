<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KandidatSpkluTerdekat extends Model
{
    protected $table = 'kandidat_spklu_terdekat';

    protected $fillable = [
        'kandidat_id', 'nama_spklu', 'jarak_km', 'kapasitas_kw', 'status_jarak', 'sumber_jarak',
    ];

    public function kandidat()
    {
        return $this->belongsTo(KandidatPrioritas::class, 'kandidat_id');
    }
}