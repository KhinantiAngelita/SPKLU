<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreignId('pengajuan_id')->nullable()
                ->after('id')->constrained('pengajuans')->cascadeOnDelete();
            $table->foreignId('penanggung_jawab')->nullable()
                ->after('dibuat_oleh')->constrained('users')->nullOnDelete();
            $table->string('status', 30)->default('terjadwal'); // terjadwal, berlangsung, selesai, batal
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_id']);
            $table->dropForeign(['penanggung_jawab']);
            $table->dropColumn(['pengajuan_id', 'penanggung_jawab', 'status']);
        });
    }
};