<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat kunjungan tahapan — tabel TERPISAH dari probabilitas
     * (relasi belongsTo), bukan kolom checkbox tunggal. Ini sengaja
     * sesuai dokumen acuan bagian 4.4 & 7.3: satu tahap bisa dikunjungi
     * berkali-kali dengan hasil berbeda tiap kali, jadi butuh histori,
     * bukan status boolean satu kali centang.
     *
     * Urutan 11 nilai enum `tahap` mengikuti kolom "Status Tahapan" di
     * grid: Probing, Survey NPS, Surat Masuk, Survey ULP, RAB,
     * Mitra Mesin, KPP (final), PKS, Bayar BP, Pembangunan, Integrasi.
     */
    public function up(): void
    {
        Schema::create('tahapan_probings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('probabilitas_id')->constrained('probabilitas')->cascadeOnDelete();

            $table->enum('tahap', [
                'probing',
                'survey_nps',
                'surat_masuk',
                'survey_ulp',
                'rab',
                'mitra_mesin',
                'kpp_final',
                'pks',
                'bayar_bp',
                'pembangunan',
                'integrasi',
            ]);

            $table->date('tanggal');
            $table->string('petugas_pic')->nullable();

            // 3 hasil sesuai dokumen. "gagal" ditambahkan di badge sebagai
            // "✗ Nx" merah (dokumen hanya contohkan 2 badge: hijau/kuning
            // untuk Berhasil/Perlu Kunjungan Ulang — merah mengikuti pola
            // warna status semantik di bagian 6, perlu dikonfirmasi kalau
            // maksud aslinya beda).
            $table->enum('hasil', ['berhasil', 'perlu_kunjungan_ulang', 'gagal']);

            $table->text('catatan')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['probabilitas_id', 'tahap']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahapan_probings');
    }
};