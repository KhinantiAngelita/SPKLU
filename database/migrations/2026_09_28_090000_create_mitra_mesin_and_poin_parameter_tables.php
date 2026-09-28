<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Mitra Mesin
        if (! Schema::hasTable('mitra_mesin')) {
            Schema::create('mitra_mesin', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->string('keterangan')->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->unsignedSmallInteger('urutan')->default(0);
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });

            $defaultMitra = [
                'UCI Beny', 'Voltron', 'EAD', 'LAD', 'Niscala',
                'Prastiwahyu', 'TEB', 'Arista', 'PLN ES',
            ];
            $now = now();
            $mitraRows = [];
            foreach ($defaultMitra as $idx => $nama) {
                $mitraRows[] = [
                    'nama' => $nama,
                    'keterangan' => 'Mitra penyedia mesin SPKLU',
                    'is_aktif' => true,
                    'urutan' => $idx + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('mitra_mesin')->insertOrIgnore($mitraRows);
        }

        // 2. Tabel Poin Fasilitas
        if (! Schema::hasTable('poin_fasilitas')) {
            Schema::create('poin_fasilitas', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('kode')->unique();
                $table->unsignedTinyInteger('poin')->default(10);
                $table->string('keterangan')->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->unsignedSmallInteger('urutan')->default(0);
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });

            $defaultFasilitas = [
                ['nama' => 'Toilet', 'kode' => 'toilet', 'poin' => 10, 'urutan' => 1],
                ['nama' => 'Ruang Tunggu', 'kode' => 'ruang_tunggu', 'poin' => 10, 'urutan' => 2],
                ['nama' => 'Parkir', 'kode' => 'parkir', 'poin' => 10, 'urutan' => 3],
                ['nama' => 'Kafetaria', 'kode' => 'kafetaria', 'poin' => 10, 'urutan' => 4],
            ];
            $now = now();
            foreach ($defaultFasilitas as &$f) {
                $f['keterangan'] = 'Parameter fasilitas lokasi';
                $f['is_aktif'] = true;
                $f['created_at'] = $now;
                $f['updated_at'] = $now;
            }
            DB::table('poin_fasilitas')->insertOrIgnore($defaultFasilitas);
        }

        // 3. Tabel Poin Okupansi
        if (! Schema::hasTable('poin_okupansi')) {
            Schema::create('poin_okupansi', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('kode')->unique();
                $table->unsignedTinyInteger('poin')->default(10);
                $table->string('keterangan')->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->unsignedSmallInteger('urutan')->default(0);
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });

            $defaultOkupansi = [
                ['nama' => 'Dekat Perumahan', 'kode' => 'dekat_perumahan', 'poin' => 10, 'urutan' => 1],
                ['nama' => 'Dekat Pintu Tol', 'kode' => 'pintu_tol', 'poin' => 10, 'urutan' => 2],
                ['nama' => 'Pusat Keramaian', 'kode' => 'pusat_keramaian', 'poin' => 10, 'urutan' => 3],
                ['nama' => 'Ruas Jalan Protokol', 'kode' => 'ruas_jalan_protokol', 'poin' => 10, 'urutan' => 4],
            ];
            $now = now();
            foreach ($defaultOkupansi as &$o) {
                $o['keterangan'] = 'Parameter okupansi kawasan';
                $o['is_aktif'] = true;
                $o['created_at'] = $now;
                $o['updated_at'] = $now;
            }
            DB::table('poin_okupansi')->insertOrIgnore($defaultOkupansi);
        }

        // 4. Pastikan tabel poin_kesiapan_jaringan memiliki default data jika kosong
        if (Schema::hasTable('poin_kesiapan_jaringan') && DB::table('poin_kesiapan_jaringan')->count() === 0) {
            DB::table('poin_kesiapan_jaringan')->insertOrIgnore([
                ['kondisi' => 'Siap sambung', 'poin' => 20, 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['kondisi' => 'Perluasan SUTM (mudah)', 'poin' => 15, 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['kondisi' => 'Perluasan SKTM (gardu tembok)', 'poin' => 10, 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['kondisi' => 'Perluasan rumit', 'poin' => 5, 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('poin_okupansi');
        Schema::dropIfExists('poin_fasilitas');
        Schema::dropIfExists('mitra_mesin');
    }
};
