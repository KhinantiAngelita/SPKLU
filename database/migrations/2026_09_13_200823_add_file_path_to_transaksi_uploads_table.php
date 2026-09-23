<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_uploads', function (Blueprint $table) {
            // Lokasi file mentah yang tersimpan (disk default), dipakai untuk
            // fitur "Proses Ulang" 1-klik tanpa perlu pilih file lagi.
            // Nullable karena riwayat lama (sebelum fitur ini) gak punya file tersimpan.
            if (! Schema::hasColumn('transaksi_uploads', 'path_file')) {
                $table->string('path_file')->nullable()->after('ukuran_bytes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_uploads', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_uploads', 'path_file')) {
                $table->dropColumn('path_file');
            }
        });
    }
};
