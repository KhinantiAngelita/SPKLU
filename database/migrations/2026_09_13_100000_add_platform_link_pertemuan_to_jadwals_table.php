<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (! Schema::hasColumn('jadwals', 'platform')) {
                $table->string('platform')->nullable()->after('lokasi');
            }
            if (! Schema::hasColumn('jadwals', 'link_pertemuan')) {
                $table->string('link_pertemuan')->nullable()->after('platform');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('jadwals', 'platform')) {
                $drop[] = 'platform';
            }
            if (Schema::hasColumn('jadwals', 'link_pertemuan')) {
                $drop[] = 'link_pertemuan';
            }
            if (! empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
