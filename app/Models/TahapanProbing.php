<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahapanProbing extends Model
{
    use HasFactory;

    protected $fillable = [
        'probabilitas_id',
        'tahap',
        'tanggal',
        'petugas_pic',
        'hasil',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function probabilitas(): BelongsTo
    {
        return $this->belongsTo(Probabilitas::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}