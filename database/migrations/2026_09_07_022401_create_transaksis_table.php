<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spklu_id')->constrained('spklus')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('jumlah_transaksi')->default(0); // satuan "Kali"
            $table->decimal('energi_kwh', 10, 2)->default(0);
            $table->decimal('pendapatan_rp', 14, 2)->default(0);
            $table->foreignId('diupload_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['spklu_id', 'tanggal']); // dipakai berat di grafik/filter Transaksi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};