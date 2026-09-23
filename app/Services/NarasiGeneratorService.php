<?php

namespace App\Services;

use App\Models\FsSkema;

class NarasiGeneratorService
{
    public function __construct(protected FsSkemaCalculatorService $calculator) {}

    /**
     * Ringkasan analisis — pola kalimat persis contoh di sheet "Input
     * Koordinat Baru" FS Skema 3.
     *
     * $proyeksiRoi WAJIB hasil dari FsSkemaCalculatorService::hitungProyeksiROI()
     * untuk $fsSkema yang sama — dihitung sekali di controller, dilempar ke sini
     * supaya tidak dihitung dua kali.
     */
    public function buatNarasi(FsSkema $fsSkema, array $proyeksiRoi): string
    {
        $fasilitas = $this->calculator->labelFasilitasTerpilih($fsSkema->fasilitas ?? []);
        $okupansi = $this->calculator->labelOkupansiTerpilih($fsSkema->okupansi ?? []);
        $kesiapan = $fsSkema->kesiapan_jaringan ?: 'belum diketahui';

        $bagianPoin = "Lokasi {$fsSkema->nama_lokasi} memiliki fasilitas berupa {$fasilitas} dengan skor {$fsSkema->poin_fasilitas}/40. "
            ."Dari sisi kesiapan jaringan, lokasi ini berada pada kondisi '{$kesiapan}' dengan skor {$fsSkema->poin_kesiapan_jaringan}/20. "
            ."Sementara itu, dari sisi okupansi kawasan, lokasi ini berada dekat dengan {$okupansi}, memperoleh skor {$fsSkema->poin_okupansi}/40. "
            ."Secara keseluruhan, lokasi ini mendapat skor kelayakan total {$fsSkema->total_poin}/100 dan dikategorikan {$fsSkema->status_kelayakan} untuk pengembangan SPKLU.";

        $bagianEkonomi = $fsSkema->isSkema3()
            ? "Dari sisi keekonomian, estimasi balik modal (payback period) Mitra Mesin berada pada {$proyeksiRoi['estimasi_roi_mesin_teks']}, sementara Mitra Lahan diperkirakan "
                .$this->bandingkanKecepatanBep($proyeksiRoi)." pada {$proyeksiRoi['estimasi_roi_lahan_teks']}."
            : "Dari sisi keekonomian, estimasi balik modal (payback period) berada pada {$proyeksiRoi['estimasi_roi_teks']}.";

        return $bagianPoin.' '.$bagianEkonomi;
    }

    /** "lebih cepat balik modal" vs "lebih lambat balik modal", dibanding total_bulan Mesin vs Lahan. */
    protected function bandingkanKecepatanBep(array $proyeksiRoi): string
    {
        $bulanMesin = $proyeksiRoi['estimasi_bep_mesin']['total_bulan'] ?? null;
        $bulanLahan = $proyeksiRoi['estimasi_bep_lahan']['total_bulan'] ?? null;

        if ($bulanLahan === null) {
            return 'lebih lambat balik modal';
        }

        if ($bulanMesin === null) {
            return 'lebih cepat balik modal';
        }

        return $bulanLahan <= $bulanMesin ? 'lebih cepat balik modal' : 'lebih lambat balik modal';
    }
}
