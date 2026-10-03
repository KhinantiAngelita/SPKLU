<?php

namespace Database\Seeders;

use App\Models\UlpMapping;
use Illuminate\Database\Seeder;

class UlpMappingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // UP3 Bogor
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Kota', 'nama_penuh' => 'Bogor Kota', 'jarak_ideal_km' => 1.5, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Barat', 'nama_penuh' => 'Bogor Barat', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Timur', 'nama_penuh' => 'Bogor Timur', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Pakuan', 'nama_penuh' => 'Prima Pakuan (TT/TM)', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Cipayung', 'nama_penuh' => 'Cipayung', 'jarak_ideal_km' => 5.0, 'kategori_area' => 'Luar Kota'],
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Leuwiliang', 'nama_penuh' => 'Leuwiliang', 'jarak_ideal_km' => 5.0, 'kategori_area' => 'Luar Kota'],
            ['up3' => 'UP3 Bogor', 'nama_singkat' => 'Jasinga', 'nama_penuh' => 'Jasinga', 'jarak_ideal_km' => 5.0, 'kategori_area' => 'Luar Kota'],

            // UP3 Bandung
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Bdg Timur', 'nama_penuh' => 'Bandung Timur', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Bdg Barat', 'nama_penuh' => 'Bandung Barat', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Bdg Selatan', 'nama_penuh' => 'Bandung Selatan', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Bdg Utara', 'nama_penuh' => 'Bandung Utara', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Kopo', 'nama_penuh' => 'Kopo', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Cijawura', 'nama_penuh' => 'Cijawura', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bandung', 'nama_singkat' => 'Ujungberung', 'nama_penuh' => 'Ujungberung', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Depok
            ['up3' => 'UP3 Depok', 'nama_singkat' => 'Dpk Kota', 'nama_penuh' => 'Depok Kota', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Depok', 'nama_singkat' => 'Cimanggis', 'nama_penuh' => 'Cimanggis', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Depok', 'nama_singkat' => 'Sawangan', 'nama_penuh' => 'Sawangan', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Depok', 'nama_singkat' => 'Bojonggede', 'nama_penuh' => 'Bojonggede', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Bekasi
            ['up3' => 'UP3 Bekasi', 'nama_singkat' => 'Bks Kota', 'nama_penuh' => 'Bekasi Kota', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Bekasi', 'nama_singkat' => 'Prima Bks', 'nama_penuh' => 'Prima Bekasi', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bekasi', 'nama_singkat' => 'Mustika Jaya', 'nama_penuh' => 'Mustika Jaya', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Bekasi', 'nama_singkat' => 'Bantar Gebang', 'nama_penuh' => 'Bantar Gebang', 'jarak_ideal_km' => 4.0, 'kategori_area' => 'Dalam Kota'],

            // UP3 Cimahi
            ['up3' => 'UP3 Cimahi', 'nama_singkat' => 'Cmh Kota', 'nama_penuh' => 'Cimahi Kota', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Cimahi', 'nama_singkat' => 'Cmh Selatan', 'nama_penuh' => 'Cimahi Selatan', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Cimahi', 'nama_singkat' => 'Padalarang', 'nama_penuh' => 'Padalarang', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Cirebon
            ['up3' => 'UP3 Cirebon', 'nama_singkat' => 'Crb Kota', 'nama_penuh' => 'Cirebon Kota', 'jarak_ideal_km' => 2.0, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Cirebon', 'nama_singkat' => 'Sumber', 'nama_penuh' => 'Sumber', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Cirebon', 'nama_singkat' => 'Ciledug', 'nama_penuh' => 'Ciledug', 'jarak_ideal_km' => 4.5, 'kategori_area' => 'Luar Kota'],

            // UP3 Karawang
            ['up3' => 'UP3 Karawang', 'nama_singkat' => 'Krw Kota', 'nama_penuh' => 'Karawang Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Kota Padat'],
            ['up3' => 'UP3 Karawang', 'nama_singkat' => 'Kosambi', 'nama_penuh' => 'Kosambi', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Karawang', 'nama_singkat' => 'Rengasdengklok', 'nama_penuh' => 'Rengasdengklok', 'jarak_ideal_km' => 4.5, 'kategori_area' => 'Luar Kota'],

            // UP3 Cianjur
            ['up3' => 'UP3 Cianjur', 'nama_singkat' => 'Cjr Kota', 'nama_penuh' => 'Cianjur Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Cianjur', 'nama_singkat' => 'Cipanas', 'nama_penuh' => 'Cipanas', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Cianjur', 'nama_singkat' => 'Tanggeung', 'nama_penuh' => 'Tanggeung', 'jarak_ideal_km' => 5.0, 'kategori_area' => 'Luar Kota'],
            ['up3' => 'UP3 Cianjur', 'nama_singkat' => 'Sukanagara', 'nama_penuh' => 'Sukanagara', 'jarak_ideal_km' => 5.0, 'kategori_area' => 'Luar Kota'],

            // UP3 Sukabumi
            ['up3' => 'UP3 Sukabumi', 'nama_singkat' => 'Skb Kota', 'nama_penuh' => 'Sukabumi Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Sukabumi', 'nama_singkat' => 'Cibadak', 'nama_penuh' => 'Cibadak', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Sukabumi', 'nama_singkat' => 'Cicurug', 'nama_penuh' => 'Cicurug', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Sukabumi', 'nama_singkat' => 'Palabuhanratu', 'nama_penuh' => 'Palabuhanratu', 'jarak_ideal_km' => 5.0, 'kategori_area' => 'Luar Kota'],

            // UP3 Gunung Putri
            ['up3' => 'UP3 Gunung Putri', 'nama_singkat' => 'Gn Putri', 'nama_penuh' => 'Gunung Putri', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Gunung Putri', 'nama_singkat' => 'Cileungsi', 'nama_penuh' => 'Cileungsi', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Gunung Putri', 'nama_singkat' => 'Citeureup', 'nama_penuh' => 'Citeureup', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],

            // UP3 Cikarang
            ['up3' => 'UP3 Cikarang', 'nama_singkat' => 'Ckr Kota', 'nama_penuh' => 'Cikarang Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Cikarang', 'nama_singkat' => 'Cibitung', 'nama_penuh' => 'Cibitung', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Cikarang', 'nama_singkat' => 'Tambun', 'nama_penuh' => 'Tambun', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],

            // UP3 Garut
            ['up3' => 'UP3 Garut', 'nama_singkat' => 'Grt Kota', 'nama_penuh' => 'Garut Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Garut', 'nama_singkat' => 'Leles', 'nama_penuh' => 'Leles', 'jarak_ideal_km' => 4.0, 'kategori_area' => 'Dalam Kota'],

            // UP3 Tasikmalaya
            ['up3' => 'UP3 Tasikmalaya', 'nama_singkat' => 'Tsk Kota', 'nama_penuh' => 'Tasikmalaya Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Tasikmalaya', 'nama_singkat' => 'Singaparna', 'nama_penuh' => 'Singaparna', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Purwakarta
            ['up3' => 'UP3 Purwakarta', 'nama_singkat' => 'Pwk Kota', 'nama_penuh' => 'Purwakarta Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Purwakarta', 'nama_singkat' => 'Subang', 'nama_penuh' => 'Subang', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Sumedang
            ['up3' => 'UP3 Sumedang', 'nama_singkat' => 'Smd Kota', 'nama_penuh' => 'Sumedang Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Sumedang', 'nama_singkat' => 'Majalengka', 'nama_penuh' => 'Majalengka', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Indramayu
            ['up3' => 'UP3 Indramayu', 'nama_singkat' => 'Idm Kota', 'nama_penuh' => 'Indramayu Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Indramayu', 'nama_singkat' => 'Jatibarang', 'nama_penuh' => 'Jatibarang', 'jarak_ideal_km' => 3.5, 'kategori_area' => 'Dalam Kota'],

            // UP3 Majalaya
            ['up3' => 'UP3 Majalaya', 'nama_singkat' => 'Mjl Kota', 'nama_penuh' => 'Majalaya Kota', 'jarak_ideal_km' => 2.5, 'kategori_area' => 'Dalam Kota'],
            ['up3' => 'UP3 Majalaya', 'nama_singkat' => 'Baleendah', 'nama_penuh' => 'Baleendah', 'jarak_ideal_km' => 3.0, 'kategori_area' => 'Dalam Kota'],
        ];

        foreach ($data as $row) {
            UlpMapping::updateOrCreate(['nama_penuh' => $row['nama_penuh']], $row);
        }
    }
}
