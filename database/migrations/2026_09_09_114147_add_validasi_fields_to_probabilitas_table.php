<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('probabilitas', function (Blueprint $table) {
            $table->foreignId('spklu_id')->nullable()->after('id')->constrained('spklus')->nullOnDelete();
            $table->timestamp('divalidasi_pada')->nullable()->after('spklu_id');
            $table->foreignId('divalidasi_oleh')->nullable()->after('divalidasi_pada')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('probabilitas', function (Blueprint $table) {
            $table->dropForeign(['spklu_id']);
            $table->dropForeign(['divalidasi_oleh']);
            $table->dropColumn(['spklu_id', 'divalidasi_pada', 'divalidasi_oleh']);
        });
    }
};