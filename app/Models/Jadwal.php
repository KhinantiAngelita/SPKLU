<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'waktu_mulai', 'mode', 'lokasi', 'dibuat_oleh'];

    protected $casts = [
        'waktu_mulai' => 'datetime',
    ];

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}