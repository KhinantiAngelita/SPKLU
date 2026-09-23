<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fs_skemas', function (Blueprint $table) {
            if (! Schema::hasColumn('fs_skemas', 'masa_kontrak_tahun')) {
                $table->unsignedTinyInteger('masa_kontrak_tahun')->default(5)->after('transaksi_kwh_per_mobil');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fs_skemas', function (Blueprint $table) {
            if (Schema::hasColumn('fs_skemas', 'masa_kontrak_tahun')) {
                $table->dropColumn('masa_kontrak_tahun');
            }
        });
    }
};
