<?php

namespace Database\Seeders;

use App\Models\MitraMesin;
use App\Models\PoinFasilitas;
use App\Models\PoinKesiapanJaringan;
use App\Models\PoinOkupansi;
use App\Models\TarifListrik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MasterParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tarif Listrik
        if (Schema::hasTable('tarif_listrik')) {
            $tarifs = [
                ['kode' => 'TM', 'tarif_per_kwh' => 1752.68],
                ['kode' => 'TR', 'tarif_per_kwh' => 1022.08],
                ['kode' => 'LTR', 'tarif_per_kwh' => 822.26],
            ];
            foreach ($tarifs as $t) {
                TarifListrik::firstOrCreate(['kode' => $t['kode']], $t);
            }
        }

        // 2. Poin Kesiapan Jaringan
        if (Schema::hasTable('poin_kesiapan_jaringan')) {
            $jaringan = [
                ['kondisi' => 'Siap sambung', 'poin' => 20, 'urutan' => 1],
                ['kondisi' => 'Perluasan SUTM (mudah)', 'poin' => 15, 'urutan' => 2],
                ['kondisi' => 'Perluasan SKTM (gardu tembok)', 'poin' => 10, 'urutan' => 3],
                ['kondisi' => 'Perluasan rumit', 'poin' => 5, 'urutan' => 4],
            ];
            foreach ($jaringan as $j) {
                PoinKesiapanJaringan::firstOrCreate(['kondisi' => $j['kondisi']], $j);
            }
        }

        // 3. Mitra Mesin
        if (Schema::hasTable('mitra_mesin')) {
            $mitraList = [
                'UCI Beny', 'Voltron', 'EAD', 'LAD', 'Niscala',
                'Prastiwahyu', 'TEB', 'Arista', 'PLN ES',
            ];
            foreach ($mitraList as $idx => $nama) {
                MitraMesin::firstOrCreate(['nama' => $nama], [
                    'keterangan' => 'Mitra penyedia mesin SPKLU',
                    'is_aktif' => true,
                    'urutan' => $idx + 1,
                ]);
            }
        }

        // 4. Poin Fasilitas
        if (Schema::hasTable('poin_fasilitas')) {
            $fasilitas = [
                ['nama' => 'Toilet', 'kode' => 'toilet', 'poin' => 10, 'urutan' => 1],
                ['nama' => 'Ruang Tunggu', 'kode' => 'ruang_tunggu', 'poin' => 10, 'urutan' => 2],
                ['nama' => 'Parkir', 'kode' => 'parkir', 'poin' => 10, 'urutan' => 3],
                ['nama' => 'Kafetaria', 'kode' => 'kafetaria', 'poin' => 10, 'urutan' => 4],
            ];
            foreach ($fasilitas as $f) {
                PoinFasilitas::firstOrCreate(['kode' => $f['kode']], [
                    'nama' => $f['nama'],
                    'poin' => $f['poin'],
                    'urutan' => $f['urutan'],
                    'keterangan' => 'Parameter fasilitas lokasi',
                    'is_aktif' => true,
                ]);
            }
        }

        // 5. Poin Okupansi
        if (Schema::hasTable('poin_okupansi')) {
            $okupansi = [
                ['nama' => 'Dekat Perumahan', 'kode' => 'dekat_perumahan', 'poin' => 10, 'urutan' => 1],
                ['nama' => 'Dekat Pintu Tol', 'kode' => 'pintu_tol', 'poin' => 10, 'urutan' => 2],
                ['nama' => 'Pusat Keramaian', 'kode' => 'pusat_keramaian', 'poin' => 10, 'urutan' => 3],
                ['nama' => 'Ruas Jalan Protokol', 'kode' => 'ruas_jalan_protokol', 'poin' => 10, 'urutan' => 4],
            ];
            foreach ($okupansi as $o) {
                PoinOkupansi::firstOrCreate(['kode' => $o['kode']], [
                    'nama' => $o['nama'],
                    'poin' => $o['poin'],
                    'urutan' => $o['urutan'],
                    'keterangan' => 'Parameter okupansi kawasan',
                    'is_aktif' => true,
                ]);
            }
        }
    }
}
