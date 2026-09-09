<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'spklu_id', 'tanggal', 'jumlah_transaksi', 'energi_kwh', 'pendapatan_rp',
        'diupload_oleh', 'transaksi_upload_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function spklu()
    {
        return $this->belongsTo(Spklu::class)->withTrashed();
    }

    public function diuploadOleh()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }

    public function transaksiUpload()
    {
        return $this->belongsTo(TransaksiUpload::class);
    }
}