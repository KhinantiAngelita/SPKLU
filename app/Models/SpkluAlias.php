<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkluAlias extends Model
{
    protected $fillable = ['nama_asli', 'spklu_id', 'dibuat_oleh'];

    public function spklu()
    {
        return $this->belongsTo(Spklu::class);
    }
}