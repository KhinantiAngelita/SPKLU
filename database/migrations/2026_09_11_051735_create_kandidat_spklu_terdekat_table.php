<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kandidat_spklu_terdekat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandidat_id')->constrained('kandidat_prioritas')->cascadeOnDelete();
            $table->string('nama_spklu');
            $table->decimal('jarak_km', 8, 2);
            $table->decimal('kapasitas_kw', 8, 2)->nullable();
            $table->string('status_jarak')->nullable();
            $table->enum('sumber_jarak', ['manual', 'google_api']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kandidat_spklu_terdekat');
    }
};
