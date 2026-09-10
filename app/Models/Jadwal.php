<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'judul', 'deskripsi', 'waktu_mulai', 'mode', 'lokasi', 'dibuat_oleh',
        'pengajuan_id', 'probabilitas_id', 'penanggung_jawab', 'status',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
    ];

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function probabilitas()
    {
        return $this->belongsTo(Probabilitas::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab');
    }
}