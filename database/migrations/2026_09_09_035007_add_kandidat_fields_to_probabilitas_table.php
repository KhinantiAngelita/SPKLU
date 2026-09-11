<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('probabilitas', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('lokasi');
            $table->string('nomor_telepon', 30)->nullable()->after('alamat');
            $table->string('pic')->nullable()->after('nomor_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('probabilitas', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'nomor_telepon', 'pic']);
        });
    }
};              