<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_listrik', function (Blueprint $table) {
            $table->id();
            $table->enum('kode', ['TM', 'TR', 'LTR'])->unique();
            $table->decimal('tarif_per_kwh', 10, 2);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('poin_kesiapan_jaringan', function (Blueprint $table) {
            $table->id();
            $table->string('kondisi');            // "Siap sambung", "Perluasan SUTM (mudah)", dst
            $table->unsignedTinyInteger('poin');  // 0-20
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('target_tahunan', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun')->unique();
            $table->unsignedInteger('target_jumlah_spklu');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_tahunan');
        Schema::dropIfExists('poin_kesiapan_jaringan');
        Schema::dropIfExists('tarif_listrik');
    }
};