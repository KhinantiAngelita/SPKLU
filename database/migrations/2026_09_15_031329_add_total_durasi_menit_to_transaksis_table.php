<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Total durasi charging (dalam menit) untuk spklu_id + tanggal ini.
            // Dipakai buat skor kepadatan yang lebih akurat di Rekomendasi Lokasi
            // (durasi = seberapa lama unit "dipake"/nge-block antrian, beda
            // dengan jumlah_transaksi yang cuma ngitung berapa KALI dipake).
            $table->decimal('total_durasi_menit', 10, 2)->default(0)->after('energi_kwh');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('total_durasi_menit');
        });
    }
};