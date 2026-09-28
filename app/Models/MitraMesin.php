<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraMesin extends Model
{
    protected $table = 'mitra_mesin';

    protected $fillable = [
        'nama',
        'keterangan',
        'is_aktif',
        'urutan',
        'updated_by',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
