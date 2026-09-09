<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spklus', function (Blueprint $table) {
            $table->string('id_spklu_sumber')->nullable()->after('kode_unit');
        });
    }

    public function down(): void
    {
        Schema::table('spklus', function (Blueprint $table) {
            $table->dropColumn('id_spklu_sumber');
        });
    }
};