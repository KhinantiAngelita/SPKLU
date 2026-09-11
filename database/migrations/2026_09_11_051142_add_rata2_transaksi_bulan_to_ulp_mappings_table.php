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
            $table->decimal('rata2_transaksi_bulan', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ulp_mappings', function (Blueprint $table) {
            $table->dropColumn('rata2_transaksi_bulan');
        });
    }
};
