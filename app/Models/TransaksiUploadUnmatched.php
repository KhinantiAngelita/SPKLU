<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiUploadUnmatched extends Model
{
    protected $table = 'transaksi_upload_unmatched';

    protected $fillable = ['transaksi_upload_id', 'nama_asli', 'jumlah_baris'];

    public function transaksiUpload()
    {
        return $this->belongsTo(TransaksiUpload::class);
    }
}