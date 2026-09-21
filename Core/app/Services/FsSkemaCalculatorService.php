<?php

namespace App\Services;

use App\Models\FsSkema;
use App\Models\PoinKesiapanJaringan;
use App\Models\Spklu;
use App\Models\TarifListrik;
use App\Models\Transaksi;

class FsSkemaCalculatorService
{
    /**
     * FALLBACK doang — dipakai kalau tabel TarifListrik kosong/belum di-seed
     * buat kode tertentu, supaya perhitungan tetap jalan (gak throw error)
     * alih-alih hardcode permanen kayak sebelumnya. Sumber aslinya: "Tabel
     * Tarif Keuntungan/kWh per Layanan Listrik", dikonfirmasi user 2026-09-02.
     */
    protected const TARIF_FALLBACK_KWH = [
        'TM' => 1752.68,
        'TR' => 1022.08,
        'LTR' => 822.26,
    ];

    /** Fallback kalau kondisi jaringan yang diinput gak match apapun di tabel PoinKesiapanJaringan. */
    protected const POIN_JARINGAN_FALLBACK = 5;

    /**
     * Sumber "TABEL POIN KESIAPAN JARINGAN" — satu sumber kebenaran dipakai
     * juga oleh dropdown di Blade, supaya label yang tampil & yang tersimpan
     * di database selalu sinkron. Nilai poin di sini cuma buat LABEL dropdown
     * (biar gampang lihat "opsi apa aja + kira-kira berapa poin"); poin YANG
     * BENERAN DIPAKAI buat ngitung tetap dari tabel PoinKesiapanJaringan lewat
     * hitungPoinKesiapanJaringan(), supaya edit di Master Parameter beneran
     * ngaruh tanpa perlu ganti kode.
     */
    public const OPSI_KESIAPAN_JARINGAN = [
        'Siap sambung' => 20,
        'Perluasan SUTM (mudah)' => 15,
        'Perluasan SKTM (gardu tembok)' => 10,
        'Perluasan rumit' => 5,
    ];

    /** Dipakai juga oleh NarasiGeneratorService & Blade (create/edit). */
    public const LABEL_FASILITAS = [
        'toilet' => 'Toilet',
        'ruang_tunggu' => 'Ruang Tunggu',
        'parkir' => 'Parkir',
        'kafetaria' => 'Kafetaria',
    ];

    /**
     * PENTING: "Dekat Pintu Tol" (bukan cuma "Pintu Tol") — sesuai teks
     * persis di sheet "Input Koordinat Baru".
     */
    public const LABEL_OKUPANSI = [
        'dekat_perumahan' => 'Dekat Perumahan',
        'pintu_tol' => 'Dekat Pintu Tol',
        'pusat_keramaian' => 'Pusat Keramaian',
        'ruas_jalan_protokol' => 'Ruas Jalan Protokol',
    ];

    protected const GROWTH_RATE_TAHUNAN = 0.10;
    protected const POTONGAN_PLN = 0.02;
    protected const MASA_KONTRAK_DEFAULT = 5;

    /** Cache per-instance biar gak query berkali-kali dalam satu request. */
    private ?array $tarifCache = null;
    private ?array $poinJaringanCache = null;

    public function hitungPoinFasilitas(array $fasilitas): int
    {
        return min(count($fasilitas) * 10, 40);
    }

    /** "Toilet, Ruang Tunggu, Parkir" — urutan ikut LABEL_FASILITAS, bukan urutan klik user. */
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

    /**
     * Sekarang baca dari tabel PoinKesiapanJaringan (Master Parameter),
     * bukan hardcode. Exact match dulu terhadap kolom `kondisi`, baru
     * fallback ke partial match kalau formatnya beda dikit, baru fallback
     * ke POIN_JARINGAN_FALLBACK kalau beneran gak ketemu sama sekali.
     */
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

    /**
     * Sesuai rumus Excel resmi:
     *   =IF(AND(E22>=85,E19>30,E20>10,E21>30), "Layak",
     *      IF(OR(E22>=70, AND(E19>=20,E19<=30,E21>=20,E21<=30,E20>10)),
     *         "Menjadi Pertimbangan", "Kurang Direkomendasikan"))
     * Pemetaan: E19=poin Fasilitas (maks 40), E20=poin Kesiapan Jaringan (maks 20),
     * E21=poin Okupansi (maks 40), E22=Total Poin (maks 100).
     */
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

    /**
     * Proyeksi ROI sepanjang Masa Kontrak (tahun, fallback 5 tahun untuk
     * data lama sebelum kolom ini ada). Untuk Skema 2 (tanpa split RAB):
     * pendapatan penuh masuk ke satu pihak. Untuk Skema 3: dipecah Mitra
     * Mesin/Mitra Lahan/PLN, BEP dicek TERPISAH per pihak terhadap RAB
     * masing-masing.
     */
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

    /**
     * Ambil tarif keuntungan/kWh dari tabel TarifListrik (Master Parameter),
     * di-cache per-instance biar gak query berkali-kali. Fallback ke
     * TARIF_FALLBACK_KWH kalau kode-nya belum ada row-nya di database sama
     * sekali (mis. sebelum di-seed).
     */
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

    /**
     * Ambil map [kondisi (lowercase) => poin] dari tabel PoinKesiapanJaringan
     * (Master Parameter), di-cache per-instance.
     */
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
     * Estimasi "bulan ke-" BEP tercapai — cari tahun pertama kumulatif >= RAB,
     * lalu interpolasi linear di dalam tahun itu buat estimasi bulannya.
     * Null kalau belum BEP sampai akhir masa kontrak.
     *
     * ⚠️ BELUM DIKONFIRMASI sama persis dengan rumus tersembunyi di
     * spreadsheet (kolom bantu "Estimasi Bulan ke-" di sana kasih angka
     * beda dari interpolasi linear standar ini). Yang SUDAH pasti sama:
     * "tahun" (baris BEP pertama tercapai) — itu murni look-up tabel.
     * Begitu rumus bulan yang pasti dikonfirmasi, cuma bagian
     * $bulanDalamTahunIni yang perlu diganti, field 'tahun' tetap sama.
     *
     * @return array{tahun:int, total_bulan:int}|null null kalau belum BEP.
     */
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

        return null; // belum BEP dalam masa kontrak
    }

    /** Format "Tahun ke-2 (24 bulan / 2 tahun 0 bulan)" — sama pola teks spreadsheet. */
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

    /**
     * [TIDAK DIPAKAI — dipertahankan sebagai referensi/fallback darurat,
     * lihat catatan di versi controller sebelumnya. Tidak diubah oleh
     * revisi ini.]
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

    /** [TIDAK DIPAKAI — lihat catatan di hitungJarakKm().] */
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