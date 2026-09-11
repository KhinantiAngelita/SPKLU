<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fs_skemas', function (Blueprint $table) {
            if (! Schema::hasColumn('fs_skemas', 'kandidat_id')) {
                $table->unsignedBigInteger('kandidat_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('fs_skemas', 'skema')) {
                $table->string('skema', 20)->after('kandidat_id'); // skema_2 / skema_3
            }
            if (! Schema::hasColumn('fs_skemas', 'nama_lokasi')) {
                $table->string('nama_lokasi')->after('skema');
            }
            if (! Schema::hasColumn('fs_skemas', 'titik_koordinat')) {
                $table->string('titik_koordinat')->nullable()->after('nama_lokasi');
            }

            // Skema 2 pakai ini (RAB tunggal). Skema 3 pakai 2 kolom di bawahnya, bukan ini.
            if (! Schema::hasColumn('fs_skemas', 'total_rab_investasi')) {
                $table->decimal('total_rab_investasi', 18, 2)->nullable()->after('titik_koordinat');
            }

            // [BARU] Khusus Skema 3 — RAB terpisah per pihak.
            if (! Schema::hasColumn('fs_skemas', 'rab_mitra_mesin')) {
                $table->decimal('rab_mitra_mesin', 18, 2)->nullable()->after('total_rab_investasi');
            }
            if (! Schema::hasColumn('fs_skemas', 'rab_mitra_lahan')) {
                $table->decimal('rab_mitra_lahan', 18, 2)->nullable()->after('rab_mitra_mesin');
            }
            // [BARU] Sharing Provit (%) default Mitra Lahan — input user, contoh Excel 0.10 (10%).
            if (! Schema::hasColumn('fs_skemas', 'sharing_provit_mitra_lahan')) {
                $table->decimal('sharing_provit_mitra_lahan', 5, 4)->nullable()->default(0.10)->after('rab_mitra_lahan');
            }

            if (! Schema::hasColumn('fs_skemas', 'layanan_listrik')) {
                $table->string('layanan_listrik', 5)->nullable()->after('sharing_provit_mitra_lahan'); // TM/TR/LTR
            }
            if (! Schema::hasColumn('fs_skemas', 'mobil_per_hari')) {
                $table->unsignedInteger('mobil_per_hari')->default(0)->after('layanan_listrik');
            }
            if (! Schema::hasColumn('fs_skemas', 'transaksi_kwh_per_mobil')) {
                $table->decimal('transaksi_kwh_per_mobil', 10, 2)->default(0)->after('mobil_per_hari');
            }

            if (! Schema::hasColumn('fs_skemas', 'fasilitas')) {
                $table->json('fasilitas')->nullable()->after('transaksi_kwh_per_mobil');
            }
            if (! Schema::hasColumn('fs_skemas', 'poin_fasilitas')) {
                $table->unsignedTinyInteger('poin_fasilitas')->default(0)->after('fasilitas');
            }
            if (! Schema::hasColumn('fs_skemas', 'kesiapan_jaringan')) {
                $table->string('kesiapan_jaringan')->nullable()->after('poin_fasilitas');
            }
            if (! Schema::hasColumn('fs_skemas', 'poin_kesiapan_jaringan')) {
                $table->unsignedTinyInteger('poin_kesiapan_jaringan')->default(0)->after('kesiapan_jaringan');
            }
            if (! Schema::hasColumn('fs_skemas', 'okupansi')) {
                $table->json('okupansi')->nullable()->after('poin_kesiapan_jaringan');
            }
            if (! Schema::hasColumn('fs_skemas', 'poin_okupansi')) {
                $table->unsignedTinyInteger('poin_okupansi')->default(0)->after('okupansi');
            }
            if (! Schema::hasColumn('fs_skemas', 'total_poin')) {
                $table->unsignedTinyInteger('total_poin')->default(0)->after('poin_okupansi');
            }
            if (! Schema::hasColumn('fs_skemas', 'status_kelayakan')) {
                $table->string('status_kelayakan')->nullable()->after('total_poin');
            }
            if (! Schema::hasColumn('fs_skemas', 'narasi_analisis')) {
                $table->text('narasi_analisis')->nullable()->after('status_kelayakan');
            }
            if (! Schema::hasColumn('fs_skemas', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('narasi_analisis')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('fs_skemas', function (Blueprint $table) {
            $table->dropColumn([
                'kandidat_id', 'skema', 'nama_lokasi', 'titik_koordinat',
                'total_rab_investasi', 'rab_mitra_mesin', 'rab_mitra_lahan', 'sharing_provit_mitra_lahan',
                'layanan_listrik', 'mobil_per_hari', 'transaksi_kwh_per_mobil',
                'fasilitas', 'poin_fasilitas', 'kesiapan_jaringan', 'poin_kesiapan_jaringan',
                'okupansi', 'poin_okupansi', 'total_poin', 'status_kelayakan', 'narasi_analisis',
            ]);
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};