<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ditulis DEFENSIF (cek hasTable()/hasColumn() sebelum bikin/nambah apa
     * pun) mengikuti konvensi yang sudah dipakai di project ini — aman
     * dijalankan berkali-kali tanpa peduli kondisi tabel kandidat_prioritas
     * saat ini (belum ada sama sekali / sudah ada sebagian kolom).
     */
    public function up(): void
    {
        if (!Schema::hasTable('kandidat_prioritas')) {
            Schema::create('kandidat_prioritas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_lokasi');
                $table->foreignId('ulp_mapping_id')->nullable()
                    ->constrained('ulp_mappings')->nullOnDelete();
                $table->string('koordinat')->nullable()->comment('format "lat, lng"');
                $table->string('mitra_mesin')->nullable();
                $table->string('type_kw')->nullable();
                $table->string('kepemilikan')->nullable();
                $table->unsignedTinyInteger('poin_fasilitas')->default(0);
                $table->unsignedTinyInteger('poin_jaringan')->default(0);
                $table->unsignedTinyInteger('poin_okupasi')->default(0);
                $table->unsignedTinyInteger('skor_prioritas')->default(0);
                $table->unsignedTinyInteger('demand_ulp')->nullable();
                $table->unsignedTinyInteger('kebutuhan_ulp')->nullable();
                $table->boolean('jarak_real_diisi')->default(false);
                $table->timestamps();
            });

            return;
        }

        Schema::table('kandidat_prioritas', function (Blueprint $table) {
            if (!Schema::hasColumn('kandidat_prioritas', 'ulp_mapping_id')) {
                $table->foreignId('ulp_mapping_id')->nullable()->after('nama_lokasi')
                    ->constrained('ulp_mappings')->nullOnDelete();
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'koordinat')) {
                $table->string('koordinat')->nullable()->after('ulp_mapping_id');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'mitra_mesin')) {
                $table->string('mitra_mesin')->nullable()->after('koordinat');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'type_kw')) {
                $table->string('type_kw')->nullable()->after('mitra_mesin');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'kepemilikan')) {
                $table->string('kepemilikan')->nullable()->after('type_kw');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'poin_fasilitas')) {
                $table->unsignedTinyInteger('poin_fasilitas')->default(0)->after('kepemilikan');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'poin_jaringan')) {
                $table->unsignedTinyInteger('poin_jaringan')->default(0)->after('poin_fasilitas');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'poin_okupasi')) {
                $table->unsignedTinyInteger('poin_okupasi')->default(0)->after('poin_jaringan');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'skor_prioritas')) {
                $table->unsignedTinyInteger('skor_prioritas')->default(0)->after('poin_okupasi');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'demand_ulp')) {
                $table->unsignedTinyInteger('demand_ulp')->nullable()->after('skor_prioritas');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'kebutuhan_ulp')) {
                $table->unsignedTinyInteger('kebutuhan_ulp')->nullable()->after('demand_ulp');
            }
            if (!Schema::hasColumn('kandidat_prioritas', 'jarak_real_diisi')) {
                $table->boolean('jarak_real_diisi')->default(false)->after('kebutuhan_ulp');
            }
        });
    }

    public function down(): void
    {
        // Sengaja tidak drop apa pun — konsisten dengan pola migration
        // defensif project ini yang menghindari rollback destruktif.
    }
};