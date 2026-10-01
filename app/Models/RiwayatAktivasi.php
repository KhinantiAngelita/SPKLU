<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatAktivasi extends Model
{
    protected $table = 'riwayat_aktivasi';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'role',
        'up3',
        'metode_aktivasi',
        'ip_address',
        'user_agent',
        'diaktivasi_pada',
    ];

    protected $casts = [
        'diaktivasi_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
