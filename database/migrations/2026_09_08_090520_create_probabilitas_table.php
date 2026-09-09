<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Semua kolom penilaian dibuat NULLABLE / default 0 secara sengaja —
     * sesuai kebutuhan: lokasi baru boleh disimpan hanya dengan identitas
     * dasar (lokasi, TIKOR, ULP, skema), sisanya diisi belakangan lewat
     * menu Edit, tidak wajib lengkap di awal.
     */
    public function up(): void
    {
        Schema::create('probabilitas', function (Blueprint $table) {
            $table->id();

            // Identitas lokasi kandidat — wajib diisi saat tambah baru.
            $table->string('lokasi');
            $table->decimal('tikor_lat', 10, 7);
            $table->decimal('tikor_lng', 10, 7);
            $table->string('ulp'); // TODO: ganti ke foreignId('ulp_mapping_id') kalau tabel ulp_mappings sudah ada
            $table->string('skema')->nullable();

            // Kebutuhan Mesin (unit) per kapasitas — sesuai kolom di grid.
            $table->unsignedTinyInteger('kebutuhan_22kw')->default(0);
            $table->unsignedTinyInteger('kebutuhan_30kw')->default(0);
            $table->unsignedTinyInteger('kebutuhan_50kw')->default(0);
            $table->unsignedTinyInteger('kebutuhan_60kw')->default(0);
            $table->unsignedTinyInteger('kebutuhan_120kw')->default(0);
            $table->unsignedTinyInteger('kebutuhan_180kw')->default(0);

            $table->string('mitra_mesin')->nullable();
            $table->unsignedTinyInteger('poin_perluasan_jaringan')->nullable();

            // Poin Fasilitas (0 = tidak ada, 1 = ada)
            $table->boolean('fasilitas_ruang_tunggu')->default(false);
            $table->boolean('fasilitas_parkir')->default(false);
            $table->boolean('fasilitas_toilet')->default(false);
            $table->boolean('fasilitas_kafe')->default(false);

            // Poin Okupansi (0 = tidak ada, 1 = ada)
            $table->boolean('okupansi_perumahan')->default(false);
            $table->boolean('okupansi_pintu_tol')->default(false);
            $table->boolean('okupansi_pusat_keramaian')->default(false);
            $table->boolean('okupansi_ruas_jalan')->default(false);

            $table->text('keterangan')->nullable();

            // Cache hasil hitung — sumber kebenaran tetap di tabel
            // tahapan_probings, tapi disimpan di sini supaya grid tidak
            // perlu hitung ulang tiap kali listing/filter/sort.
            $table->unsignedTinyInteger('jumlah_tahap_selesai')->default(0);
            $table->decimal('persentase_progres', 5, 2)->default(0);
            $table->enum('kategori', ['>50%', '<50%'])->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('ulp');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('probabilitas');
    }
};