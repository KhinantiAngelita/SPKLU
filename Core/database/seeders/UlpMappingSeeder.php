<?php

namespace Database\Seeders;

use App\Models\UlpMapping;
use Illuminate\Database\Seeder;

class UlpMappingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_singkat' => 'Kota',    'nama_penuh' => 'Bogor Kota',              'jarak_ideal_km' => 1.5, 'kategori_area' => 'Kota Padat'],
            ['nama_singkat' => 'Barat',   'nama_penuh' => 'Bogor Barat',             'jarak_ideal_km' => 3,   'kategori_area' => 'Dalam Kota'],
            ['nama_singkat' => 'Timur',   'nama_penuh' => 'Bogor Timur',             'jarak_ideal_km' => 3,   'kategori_area' => 'Dalam Kota'],
            ['nama_singkat' => 'Pakuan',  'nama_penuh' => 'Prima Pakuan (TT/TM)',    'jarak_ideal_km' => 3,   'kategori_area' => 'Dalam Kota'],
            ['nama_singkat' => 'Cipayung','nama_penuh' => 'Cipayung',                'jarak_ideal_km' => 5,   'kategori_area' => 'Luar Kota'],
            ['nama_singkat' => 'Leuwiliang','nama_penuh' => 'Leuwiliang',            'jarak_ideal_km' => 5,   'kategori_area' => 'Luar Kota'],
            ['nama_singkat' => 'Jasinga', 'nama_penuh' => 'Jasinga',                 'jarak_ideal_km' => 5,   'kategori_area' => 'Luar Kota'],
        ];

        foreach ($data as $row) {
            UlpMapping::firstOrCreate(['nama_penuh' => $row['nama_penuh']], $row);
        }
    }
}