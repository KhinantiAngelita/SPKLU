<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->string('status', 30)->default('diajukan')->after('id');
            // sesuaikan urutan status yang dipakai di aplikasi kamu, misal:
            // diajukan, diverifikasi, tervalidasi, disetujui, ditolak
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};