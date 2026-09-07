<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifListrik extends Model
{
    protected $table = 'tarif_listrik';

    protected $fillable = ['kode', 'tarif_per_kwh', 'updated_by'];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}