<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetTahunan extends Model
{
    protected $table = 'target_tahunan';

    protected $fillable = ['tahun', 'target_jumlah_spklu', 'updated_by'];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}