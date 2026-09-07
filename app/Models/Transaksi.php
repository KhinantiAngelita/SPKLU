<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'spklu_id', 'tanggal', 'jumlah_transaksi', 'energi_kwh', 'pendapatan_rp', 'diupload_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function spklu()
    {
        return $this->belongsTo(Spklu::class);
    }

    public function diuploadOleh()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }
}