<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kandidat_prioritas', function (Blueprint $table) {
            $table->decimal('poin_jaringan', 4, 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('kandidat_prioritas', function (Blueprint $table) {
            $table->integer('poin_jaringan')->nullable()->change();
        });
    }
};