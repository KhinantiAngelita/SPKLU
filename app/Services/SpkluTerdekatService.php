<?php

namespace App\Services;

use App\Models\Spklu;
use App\Models\Probabilitas;
use App\Models\KandidatPrioritas;

/**
 * SENGAJA dihitung on-the-fly (tidak pernah disimpan ke kolom database)
 * karena Master SPKLU bisa nambah lokasi baru kapan saja lewat
 * replace-on-import — sama alasan seperti fitur "3 SPKLU Terdekat" di FS
 * Skema.
 */
class SpkluTerdekatService
{
    public function hitungJarakKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $bumiRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($bumiRadiusKm * $c, 2);
    }

    /**
     * ASUMSI SKEMA (sesuaikan kalau beda di project kamu):
     *   - tabel `master_spklus`: nama_lokasi, status ('aktif'/...),
     *     latitude, longitude, ulp_mapping_id
     *   - model MasterSpklu punya relasi ulpMapping()
     *   - UlpMapping punya kolom jarak_ideal_km & nama_ulp
     *
     * @return array<int, array{nama:string, jarak_km:float, latitude:float, longitude:float, ulp:?string, status_jarak:string}>
     */
    public function cariTerdekat(float $lat, float $lng, int $jumlah = 3): array
    {
        $daftarSpklu = Spklu::query()
            ->where('status', 'aktif')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('ulp')
            ->get();

        $hasil = $daftarSpklu->map(function (Spklu $spklu) use ($lat, $lng) {
            $jarak = $this->hitungJarakKm($lat, $lng, (float) $spklu->latitude, (float) $spklu->longitude);
            $jarakIdeal = $spklu->ulp->jarak_ideal_km ?? null;

            $statusJarak = 'Jarak ideal ULP belum diatur';
            if ($jarakIdeal !== null) {
                $statusJarak = $jarak < $jarakIdeal
                    ? 'Tidak Bagus (berisiko kanibalisasi)'
                    : 'Bagus';
            }

            return [
                'nama' => $spklu->nama,
                'jarak_km' => $jarak,
                'latitude' => (float) $spklu->latitude,
                'longitude' => (float) $spklu->longitude,
                'ulp' => $spklu->ulp->nama_penuh ?? null,
                'status_jarak' => $statusJarak,
            ];
        });

        return $hasil->sortBy('jarak_km')->take($jumlah)->values()->all();
    }

    public function hitungSkorKebutuhan(KandidatPrioritas $kandidat, ?float $jarakIdealUlp): float
    {
        $tiga = $kandidat->spkluTerdekat()->orderBy('id')->limit(3)->get();

        if ($tiga->count() < 3 || $jarakIdealUlp === null) {
            return 0; // sama seperti Excel: data belum lengkap -> 0
        }

        $bobot = [0.5, 0.3, 0.2];
        $totalSkor = 0;

        foreach ($tiga as $i => $spklu) {
            $rasioJarak = min($spklu->jarak_km / $jarakIdealUlp, 1);
            $rasioKapasitas = min(($spklu->kapasitas_kw ?? 0) / 200, 1);

            $totalSkor += (100 * (0.6 * $rasioJarak + 0.4 * (1 - $rasioKapasitas))) * $bobot[$i];
        }

        return round($totalSkor, 1);
    }

    /**
     * Skor Jarak: murni jarak REAL ke 3 SPKLU terdekat dibanding jarak ideal ULP.
     * Bobot 50/30/20 sesuai urutan terdekat. Return null kalau data belum lengkap.
     */
    public function hitungSkorJarak(array $jarakReal, ?float $jarakIdealUlp): ?float
    {
        if ($jarakIdealUlp === null || in_array(null, $jarakReal, true) || count($jarakReal) < 3) {
            return null;
        }

        $bobot = [0.5, 0.3, 0.2];
        $total = 0;

        foreach (array_slice($jarakReal, 0, 3) as $i => $jarak) {
            $total += min($jarak / $jarakIdealUlp, 1) * $bobot[$i];
        }

        return round($total * 100, 1);
    }

    /**
     * Poin Kapasitas per 1 kelas kW, hasil interpolasi linear antar titik acuan
     * dari config kapasitas_poin.php. Kapasitas > titik tertinggi di-cap ke 1.0.
     */
    public function poinDariKapasitas(float $kw): float
    {
        $referensi = config('kapasitas_poin.referensi');
        ksort($referensi);

        $kwList = array_keys($referensi);

        if ($kw >= end($kwList)) {
            return 1.0;
        }

        if ($kw <= $kwList[0]) {
            return $referensi[$kwList[0]];
        }

        foreach ($kwList as $i => $batasAtas) {
            if ($kw <= $batasAtas) {
                $batasBawah = $kwList[$i - 1];
                $poinBawah = $referensi[$batasBawah];
                $poinAtas = $referensi[$batasAtas];

                // interpolasi linear antar 2 titik acuan terdekat
                $rasio = ($kw - $batasBawah) / ($batasAtas - $batasBawah);

                return round($poinBawah + $rasio * ($poinAtas - $poinBawah), 4);
            }
        }

        return 1.0;
    }

    /**
     * Skor Poin Okupansi + Fasilitas + Perluasan Jaringan (0-100).
     * Sama seperti hitungSkorPrioritas() lama di model KandidatPrioritas.
     */
    public function hitungSkorPoinOkupansi(float $poinFasilitas, float $poinJaringan, float $poinOkupasi): float
    {
        return round(($poinFasilitas + $poinJaringan + $poinOkupasi) * 10, 1);
    }

    public function hitungSkorPrioritasAkhir(?float $skorJarak, ?float $skorPoinKapasitas, float $skorPoinOkupansi): ?float
    {
        if ($skorJarak === null || $skorPoinKapasitas === null) {
            return null; // sama seperti Excel: kalau salah satu kosong, W jadi ""
        }

        return round(($skorJarak + $skorPoinKapasitas + $skorPoinOkupansi) / 3, 1);
    }

    public function hitungSkorPoinKapasitas(?Probabilitas $probabilitas): ?float
    {
        if (!$probabilitas) {
            return null;
        }

        $unit = [
            22  => $probabilitas->kebutuhan_22kw ?? 0,
            30  => $probabilitas->kebutuhan_30kw ?? 0,
            50  => $probabilitas->kebutuhan_50kw ?? 0,
            60  => $probabilitas->kebutuhan_60kw ?? 0,
            120 => $probabilitas->kebutuhan_120kw ?? 0,
            180 => $probabilitas->kebutuhan_180kw ?? 0,
        ];

        $totalUnit = array_sum($unit);

        if ($totalUnit == 0) {
            return null; // belum ada unit mesin diajukan sama sekali
        }

        $totalPoin = 0;
        foreach ($unit as $kw => $jumlah) {
            $totalPoin += $jumlah * $this->poinDariKapasitas($kw);
        }

        $poinRataRata = $totalPoin / $totalUnit; // rata-rata tertimbang per unit

        return round($poinRataRata * 100, 1);
    }
}