<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spklus', function (Blueprint $table) {
            $table->string('sumber', 30)->default('manual')->change();
        });
    }

    public function down(): void
    {
        Schema::table('spklus', function (Blueprint $table) {
            $table->enum('sumber', ['manual', 'import'])->default('manual')->change();
        });
    }
};