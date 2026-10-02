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
        ];

        foreach ($data as $row) {
            UlpMapping::updateOrCreate(['nama_penuh' => $row['nama_penuh']], $row);
        }
    }
}
