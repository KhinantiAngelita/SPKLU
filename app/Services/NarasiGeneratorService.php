<?php

namespace App\Services;

use App\Models\FsSkema;

class NarasiGeneratorService
{
    /**
     * Narasi ringkasan analisis — versi template sederhana, BUKAN AI-generated.
     * Excel dulu bikin ini lewat Apps Script terpisah; ini penggantinya di sistem
     * (native, tanpa script tempelan), tapi logikanya masih dasar (template isi
     * variabel), belum sekompleks aslinya.
     */
    public function buatNarasi(FsSkema $fsSkema): string
    {
        $namaSkema = $fsSkema->skema === 'skema_2' ? 'Skema 2 (Curah TR)' : 'Skema 3 (Mitra Mesin & Mitra Lahan)';

        $kekuatan = [];
        $kelemahan = [];

        if ($fsSkema->poin_fasilitas >= 30) {
            $kekuatan[] = 'fasilitas pendukung yang cukup lengkap';
        } elseif ($fsSkema->poin_fasilitas <= 10) {
            $kelemahan[] = 'fasilitas pendukung yang masih minim';
        }

        if ($fsSkema->poin_kesiapan_jaringan >= 15) {
            $kekuatan[] = 'kesiapan jaringan listrik yang baik';
        } elseif ($fsSkema->poin_kesiapan_jaringan <= 5) {
            $kelemahan[] = 'kesiapan jaringan listrik yang masih perlu perluasan';
        }

        if ($fsSkema->poin_okupansi >= 30) {
            $kekuatan[] = 'okupansi lokasi yang strategis';
        } elseif ($fsSkema->poin_okupansi <= 10) {
            $kelemahan[] = 'okupansi lokasi yang kurang ramai';
        }

        $teksKekuatan = $kekuatan ? implode(', ', $kekuatan) : 'tidak ada keunggulan menonjol yang tercatat';
        $teksKelemahan = $kelemahan ? ' Namun perlu diperhatikan ' . implode(', ', $kelemahan) . '.' : '';

        return "Lokasi {$fsSkema->nama_lokasi} dianalisis menggunakan {$namaSkema}, memperoleh total skor {$fsSkema->total_poin}/100 dengan status rekomendasi \"{$fsSkema->status_kelayakan}\". "
            . "Lokasi ini memiliki {$teksKekuatan}.{$teksKelemahan}";
    }
}