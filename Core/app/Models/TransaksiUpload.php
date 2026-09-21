<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiUpload extends Model
{
    protected $fillable = [
        'nama_file', 'ukuran_bytes', 'total_baris_diproses', 'total_rekap_tersimpan',
        'jumlah_nama_tidak_cocok', 'status', 'pesan_error', 'diupload_oleh',
    ];

    public function diuploadOleh()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function unmatchedNames()
    {
        return $this->hasMany(TransaksiUploadUnmatched::class);
    }
}