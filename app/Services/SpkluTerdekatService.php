<?php

namespace App\Services;

use App\Models\KandidatPrioritas;
use App\Models\Probabilitas;
use App\Models\Spklu;

class SpkluTerdekatService
{
    public function __construct(private DistanceMatrixService $distanceMatrixService) {}

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
     * @return array<int, array{nama:string, jarak_km:float, kapasitas_kw:?float, latitude:float, longitude:float, ulp:?string, status_jarak:string}>
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
                'kapasitas_kw' => $spklu->kw ?? $spklu->getEffectiveKw(),
                'latitude' => (float) $spklu->latitude,
                'longitude' => (float) $spklu->longitude,
                'ulp' => $spklu->ulp->nama_penuh ?? null,
                'status_jarak' => $statusJarak,
            ];
        });

        return $hasil->sortBy('jarak_km')->take($jumlah)->values()->all();
    }

    /**
     * Sama seperti cariTerdekat(), tapi jarak_km hasil akhirnya adalah jarak
     * JALAN REAL dari Google Routes API — bukan garis lurus. Haversine
     * dipakai sebagai pre-filter (ambil $preFilterJumlah kandidat terdekat
     * garis lurus dulu) supaya tidak perlu hitung real distance ke SELURUH
     * SPKLU aktif (boros kuota API).
     *
     * @return array<int, array{nama:string, jarak_km:float, kapasitas_kw:?float, latitude:float, longitude:float, ulp:?string, status_jarak:string, durasi_menit:?float}>
     */
    public function cariTerdekatViaApi(float $lat, float $lng, int $jumlah = 3, int $preFilterJumlah = 10): array
    {
        $kandidatAwal = collect($this->cariTerdekat($lat, $lng, $preFilterJumlah));

        if ($kandidatAwal->isEmpty()) {
            return [];
        }

        $destinations = $kandidatAwal->map(fn ($s) => [
            'lat' => $s['latitude'],
            'lng' => $s['longitude'],
        ])->all();

        $hasilApi = $this->distanceMatrixService->hitungJarak(
            ['lat' => $lat, 'lng' => $lng],
            $destinations
        );

        $gabungan = $kandidatAwal->values()->map(function ($spklu, $i) use ($hasilApi) {
            $apiRow = $hasilApi[$i] ?? null;

            return array_merge($spklu, [
                'jarak_km_real' => $apiRow['jarak_km'] ?? null,
                'durasi_menit' => $apiRow['durasi_menit'] ?? null,
            ]);
        });

        $valid = $gabungan->filter(fn ($s) => $s['jarak_km_real'] !== null);

        return $valid->sortBy('jarak_km_real')
            ->take($jumlah)
            ->map(function ($s) {
                $s['jarak_km'] = $s['jarak_km_real'];
                unset($s['jarak_km_real']);

                return $s;
            })
            ->values()
            ->all();
    }

    public function hitungSkorKebutuhan(KandidatPrioritas $kandidat, ?float $jarakIdealUlp): float
    {
        $tiga = $kandidat->spkluTerdekat()->orderBy('id')->limit(3)->get();

        if ($tiga->count() < 3 || $jarakIdealUlp === null) {
            return 0;
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

                $rasio = ($kw - $batasBawah) / ($batasAtas - $batasBawah);

                return round($poinBawah + $rasio * ($poinAtas - $poinBawah), 4);
            }
        }

        return 1.0;
    }

    public function hitungSkorPoinOkupansi(float $poinFasilitas, float $poinJaringan, float $poinOkupasi): float
    {
        return round(($poinFasilitas + $poinJaringan + $poinOkupasi) * 10, 1);
    }

    public function hitungSkorPrioritasAkhir(?float $skorJarak, ?float $skorPoinKapasitas, float $skorPoinOkupansi): ?float
    {
        if ($skorJarak === null || $skorPoinKapasitas === null) {
            return null;
        }

        return round(($skorJarak + $skorPoinKapasitas + $skorPoinOkupansi) / 3, 1);
    }

    public function hitungSkorPoinKapasitas(?Probabilitas $probabilitas): ?float
    {
        if (! $probabilitas) {
            return null;
        }

        $unit = [
            22 => $probabilitas->kebutuhan_22kw ?? 0,
            30 => $probabilitas->kebutuhan_30kw ?? 0,
            50 => $probabilitas->kebutuhan_50kw ?? 0,
            60 => $probabilitas->kebutuhan_60kw ?? 0,
            120 => $probabilitas->kebutuhan_120kw ?? 0,
            180 => $probabilitas->kebutuhan_180kw ?? 0,
        ];

        $totalUnit = array_sum($unit);

        if ($totalUnit == 0) {
            return null;
        }

        $totalPoin = 0;
        foreach ($unit as $kw => $jumlah) {
            $totalPoin += $jumlah * $this->poinDariKapasitas($kw);
        }

        $poinRataRata = $totalPoin / $totalUnit;

        return round($poinRataRata * 100, 1);
    }
}
