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
        Schema::table('kandidat_prioritas', function (Blueprint $table) {
            $table->boolean('probing')->default(false);
            $table->boolean('survey_nps')->default(false);
            $table->boolean('surat_masuk')->default(false);
            $table->boolean('survey_ulp')->default(false);
            $table->boolean('rab')->default(false);
            $table->boolean('kkp_final')->default(false);
            $table->boolean('pks')->default(false);
            $table->boolean('bayar_bp')->default(false);
            $table->boolean('pembangunan')->default(false);
            $table->boolean('integrasi')->default(false);

            $table->decimal('jarak_real_1', 8, 2)->nullable();
            $table->decimal('jarak_real_2', 8, 2)->nullable();
            $table->decimal('jarak_real_3', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kandidat_prioritas', function (Blueprint $table) {
            $table->dropColumn([
                'probing', 'survey_nps', 'surat_masuk', 'survey_ulp', 'rab',
                'kkp_final', 'pks', 'bayar_bp', 'pembangunan', 'integrasi',
                'jarak_real_1', 'jarak_real_2', 'jarak_real_3',
            ]);
        });
    }
};
