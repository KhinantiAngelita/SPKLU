<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ulp_mappings', function (Blueprint $table) {
            if (! Schema::hasColumn('ulp_mappings', 'kategori_area')) {
                $table->string('kategori_area')->nullable()->after('nama_penuh');
            }
            if (! Schema::hasColumn('ulp_mappings', 'jarak_ideal_km')) {
                $table->decimal('jarak_ideal_km', 5, 2)->nullable()->after('kategori_area');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ulp_mappings', function (Blueprint $table) {
            if (Schema::hasColumn('ulp_mappings', 'kategori_area')) {
                $table->dropColumn('kategori_area');
            }
            if (Schema::hasColumn('ulp_mappings', 'jarak_ideal_km')) {
                $table->dropColumn('jarak_ideal_km');
            }
        });
    }
};