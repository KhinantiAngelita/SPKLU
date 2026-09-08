<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_upload_unmatched', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_upload_id')->constrained('transaksi_uploads')->cascadeOnDelete();
            $table->string('nama_asli');
            $table->unsignedInteger('jumlah_baris')->default(0);
            $table->timestamps();

            $table->unique(['transaksi_upload_id', 'nama_asli']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_upload_unmatched');
    }
};