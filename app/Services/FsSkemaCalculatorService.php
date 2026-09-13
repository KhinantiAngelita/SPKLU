<?php

namespace App\Services;

use App\Models\FsSkema;
use App\Models\Spklu;
use App\Models\Transaksi;

class FsSkemaCalculatorService
{
    protected const TARIF_KEUNTUNGAN_KWH = [
        'TM' => 1752.68,
        'TR' => 1022.08,
        'LTR' => 822.26,
    ];

    protected const POIN_KESIAPAN_JARINGAN = [
        'siap sambung' => 20,
        'perluasan sutm' => 15,
        'perluasan sktm' => 10,
        'perluasan rumit' => 5,
    ];

    /** Growth rate mobil/hari per tahun — TETAP sesuai dokumen FS, bukan input user. */
    protected const GROWTH_RATE_TAHUNAN = 0.10;

    /** Potongan tetap PLN dari total keuntungan (Skema 3) — TETAP, dikonfirmasi 2%, tidak ditampilkan ke user. */
    protected const POTONGAN_PLN = 0.02;

    public function hitungPoinFasilitas(array $fasilitas): int
    {
        return min(count($fasilitas) * 10, 40);
    }

    public function hitungPoinKesiapanJaringan(?string $kesiapanJaringan): int
    {
        if (! $kesiapanJaringan) {
            return 0;
        }

        $input = strtolower(trim($kesiapanJaringan));

        foreach (self::POIN_KESIAPAN_JARINGAN as $label => $poin) {
            if (str_contains($input, $label) || str_contains($label, $input)) {
                return $poin;
            }
        }

        return 5;
    }

    public function hitungPoinOkupansi(array $okupansi): int
    {
        return min(count($okupansi) * 10, 40);
    }

    public function hitungTotalPoin(int $poinFasilitas, int $poinJaringan, int $poinOkupansi): int
    {
        return $poinFasilitas + $poinJaringan + $poinOkupansi;
    }

    /**
     * NOTE: ambang batas masih ASUMSI SEMENTARA — Excel cuma kasih 2 contoh yang
     * dua-duanya "Menjadi Pertimbangan" (85 & 75). Perlu dikonfirmasi ke pemilik proses.
     */
    public function tentukanStatusKelayakan(int $totalPoin): string
    {
        return match (true) {
            $totalPoin >= 90 => 'Layak',
            $totalPoin >= 60 => 'Menjadi Pertimbangan',
            default => 'Tidak Layak',
        };
    }

    /**
     * Proyeksi ROI 5 tahun. Untuk Skema 2 (tanpa split RAB): pendapatan penuh
     * masuk ke satu pihak. Untuk Skema 3: dipecah Mitra Mesin/Mitra Lahan/PLN,
     * BEP dicek TERPISAH per pihak terhadap RAB masing-masing.
     */
    public function hitungProyeksiROI(FsSkema $fsSkema): array
    {
        return $fsSkema->isSkema3()
            ? $this->hitungProyeksiRoiSkema3($fsSkema)
            : $this->hitungProyeksiRoiSkema2($fsSkema);
    }

    protected function hitungProyeksiRoiSkema2(FsSkema $fsSkema): array
    {
        $keuntunganPerKwh = self::TARIF_KEUNTUNGAN_KWH[strtoupper($fsSkema->layanan_listrik ?? 'TR')] ?? self::TARIF_KEUNTUNGAN_KWH['TR'];
        $rab = (float) ($fsSkema->total_rab_investasi ?? 0);

        $hasil = [];
        $mobilPerHari = $fsSkema->mobil_per_hari;
        $kumulatif = 0;

        for ($tahun = 1; $tahun <= 5; $tahun++) {
            $transaksiPerTahun = $mobilPerHari * 365;
            $energiKwhPerTahun = $transaksiPerTahun * $fsSkema->transaksi_kwh_per_mobil;
            $pendapatanTahunIni = $energiKwhPerTahun * $keuntunganPerKwh;
            $kumulatif += $pendapatanTahunIni;

            $hasil[] = [
                'tahun' => $tahun,
                'mobil_per_hari' => round($mobilPerHari, 1),
                'transaksi_per_tahun' => round($transaksiPerTahun),
                'energi_kwh_per_tahun' => round($energiKwhPerTahun),
                'pendapatan_mitra' => round($pendapatanTahunIni),
                'kumulatif' => round($kumulatif),
                'sudah_bep' => $kumulatif >= $rab,
            ];

            $mobilPerHari *= (1 + self::GROWTH_RATE_TAHUNAN);
        }

        return [
            'tipe' => 'skema_2',
            'tahunan' => $hasil,
            'estimasi_bep' => $this->estimasiBulanBep($hasil, $rab, 'pendapatan_mitra', 'kumulatif'),
        ];
    }

    protected function hitungProyeksiRoiSkema3(FsSkema $fsSkema): array
    {
        $keuntunganPerKwh = self::TARIF_KEUNTUNGAN_KWH[strtoupper($fsSkema->layanan_listrik ?? 'TR')] ?? self::TARIF_KEUNTUNGAN_KWH['TR'];
        $sharingLahan = (float) ($fsSkema->sharing_provit_mitra_lahan ?? 0.10);
        $sharingMesin = 1 - self::POTONGAN_PLN - $sharingLahan;

        $rabMesin = (float) ($fsSkema->rab_mitra_mesin ?? 0);
        $rabLahan = (float) ($fsSkema->rab_mitra_lahan ?? 0);

        $hasil = [];
        $mobilPerHari = $fsSkema->mobil_per_hari;
        $kumulatifMesin = 0;
        $kumulatifLahan = 0;

        for ($tahun = 1; $tahun <= 5; $tahun++) {
            $transaksiPerTahun = $mobilPerHari * 365;
            $energiKwhPerTahun = $transaksiPerTahun * $fsSkema->transaksi_kwh_per_mobil;
            $totalKeuntunganTahunIni = $energiKwhPerTahun * $keuntunganPerKwh;

            $pendapatanMesinTahunIni = $totalKeuntunganTahunIni * $sharingMesin;
            $pendapatanLahanTahunIni = $totalKeuntunganTahunIni * $sharingLahan;

            $kumulatifMesin += $pendapatanMesinTahunIni;
            $kumulatifLahan += $pendapatanLahanTahunIni;

            $hasil[] = [
                'tahun' => $tahun,
                'mobil_per_hari' => round($mobilPerHari, 1),
                'transaksi_per_tahun' => round($transaksiPerTahun),
                'energi_kwh_per_tahun' => round($energiKwhPerTahun),
                'pendapatan_mesin' => round($pendapatanMesinTahunIni),
                'kumulatif_mesin' => round($kumulatifMesin),
                'sudah_bep_mesin' => $kumulatifMesin >= $rabMesin,
                'pendapatan_lahan' => round($pendapatanLahanTahunIni),
                'kumulatif_lahan' => round($kumulatifLahan),
                'sudah_bep_lahan' => $kumulatifLahan >= $rabLahan,
            ];

            $mobilPerHari *= (1 + self::GROWTH_RATE_TAHUNAN);
        }

        return [
            'tipe' => 'skema_3',
            'tahunan' => $hasil,
            'estimasi_bep_mesin' => $this->estimasiBulanBep($hasil, $rabMesin, 'pendapatan_mesin', 'kumulatif_mesin'),
            'estimasi_bep_lahan' => $this->estimasiBulanBep($hasil, $rabLahan, 'pendapatan_lahan', 'kumulatif_lahan'),
        ];
    }

    /**
     * Estimasi "bulan ke-" BEP tercapai — cari tahun pertama kumulatif >= RAB,
     * lalu interpolasi linear di dalam tahun itu buat estimasi bulannya.
     * Null kalau belum BEP sampai tahun ke-5.
     */
    protected function estimasiBulanBep(array $dataTahunan, float $rab, string $kolomPendapatan, string $kolomKumulatif): ?int
    {
        if ($rab <= 0) {
            return null;
        }

        $kumulatifSebelumnya = 0;

        foreach ($dataTahunan as $baris) {
            if ($baris[$kolomKumulatif] >= $rab) {
                $sisaKebutuhan = $rab - $kumulatifSebelumnya;
                $pendapatanPerBulan = $baris[$kolomPendapatan] / 12;

                $bulanDalamTahunIni = $pendapatanPerBulan > 0 ? (int) ceil($sisaKebutuhan / $pendapatanPerBulan) : 12;
                $bulanDalamTahunIni = min($bulanDalamTahunIni, 12);

                return (($baris['tahun'] - 1) * 12) + $bulanDalamTahunIni;
            }

            $kumulatifSebelumnya = $baris[$kolomKumulatif];
        }

        return null; // belum BEP dalam 5 tahun proyeksi
    }

    /**
     * [TIDAK DIPAKAI SEJAK keputusan "3 SPKLU Terdekat numpang baca dari modul Kandidat"]
     * Dulu dipakai FsSkemaController::show() untuk hitung jarak sendiri, tapi diputuskan
     * pindah sumber data ke modul Kandidat (pakai Google Distance Matrix API — jarak rute
     * kendaraan asli, bukan garis lurus). Method ini DISIMPAN cuma sebagai referensi/
     * fallback darurat — JANGAN dipanggil dari alur aktif manapun sampai ada keputusan
     * baru untuk mengaktifkannya lagi.
     */
    public function hitungJarakKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $radiusBumiKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radiusBumiKm * $c;
    }

    /**
     * [TIDAK DIPAKAI SEJAK keputusan "3 SPKLU Terdekat numpang baca dari modul Kandidat"]
     * Lihat catatan di hitungJarakKm(). Disimpan utuh cuma buat referensi/fallback darurat.
     *
     * @return array<int, array{nama:string, latitude:float, longitude:float, jarak_km:float, kapasitas_kw:float|null, status_jarak:string, rata_rata_transaksi_kwh_bulan:float}>
     */
    public function cari3SpkluTerdekat(float $lat, float $lng): array
    {
        $semuaSpklu = Spklu::aktif()->with('ulp')->whereNotNull('latitude')->whereNotNull('longitude')->get();

        $denganJarak = $semuaSpklu->map(function ($spklu) use ($lat, $lng) {
            $jarak = $this->hitungJarakKm($lat, $lng, (float) $spklu->latitude, (float) $spklu->longitude);

            $jarakIdeal = $spklu->ulp->jarak_ideal_km ?? null;
            $statusJarak = $jarakIdeal !== null
                ? ($jarak < $jarakIdeal ? 'Tidak Bagus (berisiko kanibalisasi)' : 'Bagus')
                : 'Jarak ideal ULP belum diatur';

            $rataRataKwh = Transaksi::where('spklu_id', $spklu->id)
                ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(energi_kwh) as total")
                ->groupBy('bulan')
                ->get();

            $rataRataTransaksiKwhBulan = $rataRataKwh->isNotEmpty()
                ? round($rataRataKwh->avg('total'), 2)
                : 0.0;

            return [
                'nama' => $spklu->nama,
                'latitude' => (float) $spklu->latitude,
                'longitude' => (float) $spklu->longitude,
                'jarak_km' => round($jarak, 3),
                'kapasitas_kw' => $spklu->kw,
                'status_jarak' => $statusJarak,
                'rata_rata_transaksi_kwh_bulan' => $rataRataTransaksiKwhBulan,
            ];
        });

        return $denganJarak->sortBy('jarak_km')->take(3)->values()->all();
    }
}