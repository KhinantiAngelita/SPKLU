<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spklus', function (Blueprint $table) {
            $table->decimal('kw', 6, 2)->nullable()->change(); // jadi nullable — custom entry gak punya angka bersih
            $table->string('kw_detail')->nullable()->after('kw'); // teks asli: "22", "2x120", "80, 120 & 200"
        });
    }

    public function down(): void
    {
        Schema::table('spklus', function (Blueprint $table) {
            $table->dropColumn('kw_detail');
        });
    }
};