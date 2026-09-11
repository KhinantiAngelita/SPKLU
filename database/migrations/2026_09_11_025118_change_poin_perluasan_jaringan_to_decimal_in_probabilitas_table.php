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
        Schema::table('probabilitas', function (Blueprint $table) {
            $table->decimal('poin_perluasan_jaringan', 3, 1)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('probabilitas', function (Blueprint $table) {
            $table->unsignedTinyInteger('poin_fasilitas')->nullable()->default(null)->change();
            $table->unsignedTinyInteger('poin_perluasan_jaringan')->nullable()->default(null)->change();
            $table->unsignedTinyInteger('poin_okupasi')->nullable()->default(null)->change();
        });
    }
};
