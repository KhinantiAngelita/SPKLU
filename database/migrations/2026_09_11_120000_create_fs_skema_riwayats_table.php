<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ASUMSI: nama tabel utama FS Skema adalah `fs_skemas` (konvensi default
 * Laravel dari model `FsSkema`). Kalau di project kamu namanya beda,
 * sesuaikan foreignId()->constrained('nama_tabel_kamu') di bawah.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('fs_skema_riwayats')) {
            Schema::create('fs_skema_riwayats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('fs_skema_id')->constrained('fs_skemas')->cascadeOnDelete();

                $table->unsignedTinyInteger('poin_fasilitas');
                $table->unsignedTinyInteger('poin_kesiapan_jaringan');
                $table->unsignedTinyInteger('poin_okupansi');
                $table->unsignedTinyInteger('total_poin');
                $table->string('status_kelayakan');
                $table->text('narasi_analisis')->nullable();

                $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->index(['fs_skema_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fs_skema_riwayats');
    }
};
