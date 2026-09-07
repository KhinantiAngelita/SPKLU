<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinKesiapanJaringan extends Model
{
    protected $table = 'poin_kesiapan_jaringan';

    protected $fillable = ['kondisi', 'poin', 'urutan', 'updated_by'];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}