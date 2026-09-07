<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlpMapping extends Model
{
    protected $fillable = ['nama_singkat', 'nama_penuh', 'jarak_ideal_km', 'kategori_area'];

    public function spklus()
    {
        return $this->hasMany(Spklu::class);
    }
}