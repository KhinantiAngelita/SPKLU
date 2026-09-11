<?php

namespace App\Services;

use App\Models\Spklu;

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
            ->with('ulpMapping')
            ->get();

        $hasil = $daftarSpklu->map(function (Spklu $spklu) use ($lat, $lng) {
            $jarak = $this->hitungJarakKm($lat, $lng, (float) $spklu->latitude, (float) $spklu->longitude);
            $jarakIdeal = $spklu->ulpMapping->jarak_ideal_km ?? null;

            $statusJarak = 'Jarak ideal ULP belum diatur';
            if ($jarakIdeal !== null) {
                $statusJarak = $jarak < $jarakIdeal
                    ? 'Tidak Bagus (berisiko kanibalisasi)'
                    : 'Bagus';
            }

            return [
                'nama' => $spklu->nama_lokasi,
                'jarak_km' => $jarak,
                'latitude' => (float) $spklu->latitude,
                'longitude' => (float) $spklu->longitude,
                'ulp' => $spklu->ulpMapping->nama_penuh ?? null,
                'status_jarak' => $statusJarak,
            ];
        });

        return $hasil->sortBy('jarak_km')->take($jumlah)->values()->all();
    }
}