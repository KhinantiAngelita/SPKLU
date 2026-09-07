<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spklus', function (Blueprint $table) {
            $table->id();
            $table->string('id_spklu')->unique();
            $table->string('kode_unit')->nullable();
            $table->string('nama');
            $table->foreignId('ulp_mapping_id')->constrained('ulp_mappings');
            $table->enum('type', ['AC', 'DC']);
            $table->decimal('kw', 6, 2);
            $table->unsignedTinyInteger('nozzle')->default(1);
            $table->enum('kepemilikan', ['PLN', 'Swasta']);
            $table->unsignedTinyInteger('skema')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('status', ['menunggu_validasi', 'aktif', 'nonaktif'])->default('aktif');
            $table->enum('sumber', ['manual', 'pengajuan'])->default('manual');
            $table->unsignedBigInteger('pengajuan_id')->nullable(); // FK ditambah belakangan setelah tabel pengajuans ada (punya Person C)
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spklus');
    }
};