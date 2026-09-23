<?php

namespace App\Services;

use App\Models\FsSkema;
use App\Models\PoinKesiapanJaringan;
use App\Models\Spklu;
use App\Models\TarifListrik;
use App\Models\Transaksi;

class FsSkemaCalculatorService
{
    protected const TARIF_FALLBACK_KWH = [
        'TM' => 1752.68,
        'TR' => 1022.08,
        'LTR' => 822.26,
    ];

    protected const POIN_JARINGAN_FALLBACK = 5;

    public const OPSI_KESIAPAN_JARINGAN = [
        'Siap sambung' => 20,
        'Perluasan SUTM (mudah)' => 15,
        'Perluasan SKTM (gardu tembok)' => 10,
        'Perluasan rumit' => 5,
    ];

    public const LABEL_FASILITAS = [
        'toilet' => 'Toilet',
        'ruang_tunggu' => 'Ruang Tunggu',
        'parkir' => 'Parkir',
        'kafetaria' => 'Kafetaria',
    ];

    public const LABEL_OKUPANSI = [
        'dekat_perumahan' => 'Dekat Perumahan',
        'pintu_tol' => 'Dekat Pintu Tol',
        'pusat_keramaian' => 'Pusat Keramaian',
        'ruas_jalan_protokol' => 'Ruas Jalan Protokol',
    ];

    protected const GROWTH_RATE_TAHUNAN = 0.10;

    protected const POTONGAN_PLN = 0.02;

    protected const MASA_KONTRAK_DEFAULT = 5;

    private ?array $tarifCache = null;

    private ?array $poinJaringanCache = null;

    public function hitungPoinFasilitas(array $fasilitas): int
    {
        return min(count($fasilitas) * 10, 40);
    }

    public function labelFasilitasTerpilih(array $fasilitas): string
    {
        $label = collect(self::LABEL_FASILITAS)->only($fasilitas)->values();

        return $label->isNotEmpty() ? $label->implode(', ') : 'tidak ada fasilitas yang tercatat';
    }

    public function labelOkupansiTerpilih(array $okupansi): string
    {
        $label = collect(self::LABEL_OKUPANSI)->only($okupansi)->values();

        return $label->isNotEmpty() ? $label->implode(', ') : 'tidak ada kondisi okupansi yang tercatat';
    }

    public function hitungPoinKesiapanJaringan(?string $kesiapanJaringan): int
    {
        if (! $kesiapanJaringan) {
            return 0;
        }

        $map = $this->ambilPoinJaringanMap();
        $input = strtolower(trim($kesiapanJaringan));

        if (isset($map[$input])) {
            return $map[$input];
        }

        foreach ($map as $kondisi => $poin) {
            if (str_contains($input, $kondisi) || str_contains($kondisi, $input)) {
                return $poin;
            }
        }

        return self::POIN_JARINGAN_FALLBACK;
    }

    public function hitungPoinOkupansi(array $okupansi): int
    {
        return min(count($okupansi) * 10, 40);
    }

    public function hitungTotalPoin(int $poinFasilitas, int $poinJaringan, int $poinOkupansi): int
    {
        return $poinFasilitas + $poinJaringan + $poinOkupansi;
    }

    public function tentukanStatusKelayakan(int $totalPoin, int $poinFasilitas, int $poinKesiapanJaringan, int $poinOkupansi): string
    {
        $layak = $totalPoin >= 85
            && $poinFasilitas > 30
            && $poinKesiapanJaringan > 10
            && $poinOkupansi > 30;

        if ($layak) {
            return 'Layak';
        }

        $menjadiPertimbangan = $totalPoin >= 70
            || (
                $poinFasilitas >= 20 && $poinFasilitas <= 30
                && $poinOkupansi >= 20 && $poinOkupansi <= 30
                && $poinKesiapanJaringan > 10
            );

        return $menjadiPertimbangan ? 'Menjadi Pertimbangan' : 'Kurang Direkomendasikan';
    }

    public function hitungProyeksiROI(FsSkema $fsSkema): array
    {
        $masaKontrak = (int) ($fsSkema->masa_kontrak_tahun ?: self::MASA_KONTRAK_DEFAULT);

        return $fsSkema->isSkema3()
            ? $this->hitungProyeksiRoiSkema3($fsSkema, $masaKontrak)
            : $this->hitungProyeksiRoiSkema2($fsSkema, $masaKontrak);
    }

    protected function hitungProyeksiRoiSkema2(FsSkema $fsSkema, int $masaKontrak): array
    {
        $keuntunganPerKwh = $this->ambilTarifPerKwh($fsSkema->layanan_listrik ?? 'TR');
        $rab = (float) ($fsSkema->total_rab_investasi ?? 0);

        $hasil = [];
        $mobilPerHari = $fsSkema->mobil_per_hari;
        $kumulatif = 0;

        for ($tahun = 1; $tahun <= $masaKontrak; $tahun++) {
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
                'persen_progres' => $rab > 0 ? round(min($kumulatif / $rab, 9.99) * 100, 1) : 0,
            ];

            $mobilPerHari *= (1 + self::GROWTH_RATE_TAHUNAN);
        }

        $estimasi = $this->estimasiBulanBep($hasil, $rab, 'pendapatan_mitra', 'kumulatif');

        return [
            'tipe' => 'skema_2',
            'masa_kontrak_tahun' => $masaKontrak,
            'tahunan' => $hasil,
            'estimasi_bep' => $estimasi,
            'estimasi_roi_teks' => $this->formatEstimasiRoi($estimasi, $masaKontrak),
        ];
    }

    protected function hitungProyeksiRoiSkema3(FsSkema $fsSkema, int $masaKontrak): array
    {
        $keuntunganPerKwh = $this->ambilTarifPerKwh($fsSkema->layanan_listrik ?? 'TR');
        $sharingLahan = (float) ($fsSkema->sharing_provit_mitra_lahan ?? 0.10);
        $sharingMesin = 1 - self::POTONGAN_PLN - $sharingLahan;

        $rabMesin = (float) ($fsSkema->rab_mitra_mesin ?? 0);
        $rabLahan = (float) ($fsSkema->rab_mitra_lahan ?? 0);

        $hasil = [];
        $mobilPerHari = $fsSkema->mobil_per_hari;
        $kumulatifMesin = 0;
        $kumulatifLahan = 0;

        for ($tahun = 1; $tahun <= $masaKontrak; $tahun++) {
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
                'persen_progres_mesin' => $rabMesin > 0 ? round(min($kumulatifMesin / $rabMesin, 9.99) * 100, 1) : 0,
                'pendapatan_lahan' => round($pendapatanLahanTahunIni),
                'kumulatif_lahan' => round($kumulatifLahan),
                'sudah_bep_lahan' => $kumulatifLahan >= $rabLahan,
                'persen_progres_lahan' => $rabLahan > 0 ? round(min($kumulatifLahan / $rabLahan, 9.99) * 100, 1) : 0,
            ];

            $mobilPerHari *= (1 + self::GROWTH_RATE_TAHUNAN);
        }

        $estimasiMesin = $this->estimasiBulanBep($hasil, $rabMesin, 'pendapatan_mesin', 'kumulatif_mesin');
        $estimasiLahan = $this->estimasiBulanBep($hasil, $rabLahan, 'pendapatan_lahan', 'kumulatif_lahan');

        return [
            'tipe' => 'skema_3',
            'masa_kontrak_tahun' => $masaKontrak,
            'tahunan' => $hasil,
            'estimasi_bep_mesin' => $estimasiMesin,
            'estimasi_bep_lahan' => $estimasiLahan,
            'estimasi_roi_mesin_teks' => $this->formatEstimasiRoi($estimasiMesin, $masaKontrak),
            'estimasi_roi_lahan_teks' => $this->formatEstimasiRoi($estimasiLahan, $masaKontrak),
        ];
    }

    protected function ambilTarifPerKwh(string $layananListrik): float
    {
        if ($this->tarifCache === null) {
            $this->tarifCache = TarifListrik::pluck('tarif_per_kwh', 'kode')
                ->map(fn ($v) => (float) $v)
                ->all();
        }

        $kode = strtoupper($layananListrik ?: 'TR');

        return $this->tarifCache[$kode]
            ?? $this->tarifCache['TR']
            ?? self::TARIF_FALLBACK_KWH[$kode]
            ?? self::TARIF_FALLBACK_KWH['TR'];
    }

    protected function ambilPoinJaringanMap(): array
    {
        if ($this->poinJaringanCache === null) {
            $this->poinJaringanCache = PoinKesiapanJaringan::all()
                ->mapWithKeys(fn ($p) => [strtolower(trim($p->kondisi)) => (int) $p->poin])
                ->all();
        }

        return $this->poinJaringanCache;
    }

    /**
     * BARU: expose peta poin jaringan (Master Parameter) supaya bisa dipakai
     * buat kalkulasi INSTAN di sisi JS form create/edit — biar Ringkasan
     * Kelayakan Lokasi gak perlu nunggu roundtrip AJAX cuma buat nampilin
     * poin dari chip/select yang baru dipilih user.
     */
    public function poinJaringanMapUntukJs(): array
    {
        return $this->ambilPoinJaringanMap();
    }

    /** BARU: expose fallback poin jaringan, dipakai bareng poinJaringanMapUntukJs(). */
    public function poinJaringanFallbackUntukJs(): int
    {
        return self::POIN_JARINGAN_FALLBACK;
    }

    protected function estimasiBulanBep(array $dataTahunan, float $rab, string $kolomPendapatan, string $kolomKumulatif): ?array
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

                return [
                    'tahun' => $baris['tahun'],
                    'total_bulan' => (($baris['tahun'] - 1) * 12) + $bulanDalamTahunIni,
                ];
            }

            $kumulatifSebelumnya = $baris[$kolomKumulatif];
        }

        return null;
    }

    public function formatEstimasiRoi(?array $estimasi, int $masaKontrak): string
    {
        if ($estimasi === null) {
            return "belum tercapai dalam proyeksi {$masaKontrak} tahun";
        }

        $tahun = $estimasi['tahun'];
        $totalBulan = $estimasi['total_bulan'];
        $sisaTahun = intdiv($totalBulan, 12);
        $sisaBulan = $totalBulan % 12;

        return "Tahun ke-{$tahun} ({$totalBulan} bulan / {$sisaTahun} tahun {$sisaBulan} bulan)";
    }

    public function hitungJarakKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $radiusBumiKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radiusBumiKm * $c;
    }

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
