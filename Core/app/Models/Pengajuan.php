<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = [
        'id_pengajuan', 'fs_skema_id', 'kandidat_id', 'status',
        'catatan_verifikasi', 'diajukan_oleh', 'diverifikasi_oleh',
        'tanggal_diajukan', 'tanggal_diverifikasi',
    ];

    protected $casts = [
        'tanggal_diajukan' => 'datetime',
        'tanggal_diverifikasi' => 'datetime',
    ];

    public function fsSkema()
    {
        return $this->belongsTo(FsSkema::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class); // relasi tetap jalan, tinggal pakai kolom pengajuan_id yang baru
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}