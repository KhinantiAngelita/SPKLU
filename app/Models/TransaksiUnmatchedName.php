<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiUnmatchedName extends Model
{
    protected $fillable = ['nama_asli', 'jumlah_baris_total'];
}