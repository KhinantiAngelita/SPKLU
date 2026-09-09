<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->unsignedBigInteger('ukuran_bytes')->nullable();
            $table->unsignedInteger('total_baris_diproses')->default(0);
            $table->unsignedInteger('total_rekap_tersimpan')->default(0);
            $table->unsignedInteger('jumlah_nama_tidak_cocok')->default(0);
            $table->enum('status', ['berhasil', 'gagal'])->default('berhasil');
            $table->text('pesan_error')->nullable();
            $table->foreignId('diupload_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_uploads');
    }
};