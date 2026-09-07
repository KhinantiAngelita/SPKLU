<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spklu_aliases', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asli')->unique(); // persis seperti tertulis di file sumber (Excel transaksi, dll)
            $table->foreignId('spklu_id')->constrained('spklus')->cascadeOnDelete();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spklu_aliases');
    }
};