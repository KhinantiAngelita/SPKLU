<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'up3')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('up3')->nullable()->after('role');
            });
        }

        if (! Schema::hasTable('riwayat_aktivasi')) {
            Schema::create('riwayat_aktivasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('nama');
                $table->string('email');
                $table->string('role');
                $table->string('up3');
                $table->string('metode_aktivasi')->default('Verifikasi OTP');
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('diaktivasi_pada');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_aktivasi');

        if (Schema::hasColumn('users', 'up3')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('up3');
            });
        }
    }
};
