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
        if (! Schema::hasColumn('spklus', 'up3')) {
            Schema::table('spklus', function (Blueprint $table) {
                $table->string('up3', 100)->default('UP3 Bogor')->after('nama')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('spklus', 'up3')) {
            Schema::table('spklus', function (Blueprint $table) {
                $table->dropColumn('up3');
            });
        }
    }
};
