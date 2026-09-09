<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_unmatched_names', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asli')->unique();
            $table->unsignedInteger('jumlah_baris_total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_unmatched_names');
    }
};