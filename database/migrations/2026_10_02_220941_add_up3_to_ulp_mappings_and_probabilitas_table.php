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
        Schema::table('ulp_mappings', function (Blueprint $table) {
            $table->string('up3', 100)->default('UP3 Bogor')->after('nama_penuh')->index();
        });

        Schema::table('probabilitas', function (Blueprint $table) {
            $table->string('up3', 100)->default('UP3 Bogor')->after('ulp')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulp_mappings', function (Blueprint $table) {
            $table->dropIndex(['up3']);
            $table->dropColumn('up3');
        });

        Schema::table('probabilitas', function (Blueprint $table) {
            $table->dropIndex(['up3']);
            $table->dropColumn('up3');
        });
    }
};
