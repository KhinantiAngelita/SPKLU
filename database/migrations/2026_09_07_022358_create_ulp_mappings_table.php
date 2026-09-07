<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulp_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_singkat');   // dipakai di sheet Probabilitas dulunya
            $table->string('nama_penuh');     // dipakai di Master SPKLU / laporan
            $table->decimal('jarak_ideal_km', 5, 2)->nullable();
            $table->string('kategori_area')->nullable(); // Dalam kota / Luar kota / Kota padat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulp_mappings');
    }
};