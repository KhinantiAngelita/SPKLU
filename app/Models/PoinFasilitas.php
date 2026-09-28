<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinFasilitas extends Model
{
    protected $table = 'poin_fasilitas';

    protected $fillable = [
        'nama',
        'kode',
        'poin',
        'keterangan',
        'is_aktif',
        'urutan',
        'updated_by',
    ];

    protected $casts = [
        'poin' => 'integer',
        'is_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
