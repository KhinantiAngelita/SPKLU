<?php

namespace App\Services;

use App\Models\Spklu;
use App\Models\Transaksi;
use Illuminate\Support\Collection;

/**
 * Logic "Rekomendasi Lokasi" diekstrak dari controller supaya bisa dipakai
 * bareng Dashboard (ringkasan singkat) tanpa duplikasi rumus skor gabungan,
 * dan supaya bagian "murah" (hitung zona) terpisah dari bagian "mahal"
 * (grid scan titik rekomendasi) — Dashboard cukup panggil yang murah.
 *
 * Skor kepadatan sekarang gabungan JUMLAH TRANSAKSI + DURASI PAKAI (bukan
 * cuma jumlah transaksi seperti sebelumnya). Alasannya: jumlah transaksi
 * tinggi tapi durasi pendek beda kondisi dengan jumlah transaksi sedang
 * tapi durasi panjang (yang kedua ini justru lebih berisiko bikin antrian,
 * walau jumlah transaksinya kelihatan biasa saja).
 */
class RekomendasiLokasiService
{
    private const BULAN_UTILISASI = 12;

    private const BULAN_TREN = 3;

    private const TREN_CLAMP_PERSEN = 30.0;

    private const RADIUS_FALLBACK_KM = 3.0;

    private const BOBOT_KEPADATAN = 0.65;

    private const BOBOT_TREN = 0.35;

    // Kepadatan sekarang gabungan dua sinyal: seberapa SERING (jumlah
    // transaksi) dan seberapa LAMA (durasi) unit itu dipakai per bulan.
    private const BOBOT_FREKUENSI = 0.5;

    private const BOBOT_DURASI = 0.5;

    // Ambang kapasitas buat nentuin rekomendasi tindak lanjut SPKLU zona
    // merah: di bawah ini disarankan "Ganti Mesin" (upgrade kapasitas),
    // di atas/sama dengan ini disarankan "Tambah Unit" (kapasitas udah
    // besar, jadi penambahan charge point baru lebih make sense).
    private const AMBANG_KW_GANTI_MESIN = 60;

    private const GRID_SPASI_KM = 1.5;

    private const MAX_GRID_TITIK = 2500;

    private const MAX_TITIK_REKOMENDASI = 8;

    private const JARAK_MIN_ANTAR_REKOMENDASI_KM = 2.0;

    /**
     * Bagian "murah": titik peta + status zona + ringkasan counts.
     * Dipakai baik oleh halaman penuh maupun widget ringkas di Dashboard.
     */
    public function hitungZonaSpklu(?int $ulpId = null): array
    {
        $spkluQuery = Spklu::aktif()
            ->with('ulp')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($ulpId) {
            $spkluQuery->where('ulp_mapping_id', $ulpId);
        }

        $semuaSpklu = $spkluQuery->get();
        $analisis = $this->analisisUtilisasi($semuaSpklu);

        $titikPeta = $semuaSpklu->map(function (Spklu $spklu) use ($analisis) {
            $skor = $analisis['skorGabungan']->get($spklu->id);
            $data = $analisis['dataUtilisasi']->get($spklu->id);
            $radiusKm = $analisis['radiusById']->get($spklu->id, self::RADIUS_FALLBACK_KM);

            $status = $this->tentukanStatus($skor, $analisis['ambangBawah'], $analisis['ambangAtas']);
            $kapasitasKw = (float) ($spklu->kw ?? 0);

            return [
                'id' => $spklu->id,
                'nama' => $spklu->nama,
                'ulp' => $spklu->ulp->nama_penuh ?? null,
                'latitude' => (float) $spklu->latitude,
                'longitude' => (float) $spklu->longitude,
                'radius_km' => $radiusKm,
                'type' => strtoupper(trim($spklu->type ?? 'AC')),
                'kapasitas_kw' => $kapasitasKw,
                'rata_rata_transaksi_bulan' => $data && $data['rata_rata_jumlah'] !== null ? round($data['rata_rata_jumlah'], 1) : null,
                'rata_rata_durasi_menit_bulan' => $data && $data['rata_rata_durasi'] !== null ? round($data['rata_rata_durasi'], 0) : null,
                'tren_persen' => $data['tren_persen'] ?? null,
                'tren_label' => $data['tren_label'] ?? null,
                'skor_gabungan' => $skor !== null ? round($skor * 100, 1) : null,
                'status' => $status,
                'rekomendasi_tindak_lanjut' => $this->tentukanRekomendasiTindakLanjut($status, $kapasitasKw),
            ];
        })->values();

        $ringkasan = [
            'total_spklu' => $titikPeta->count(),
            'hijau' => $titikPeta->where('status', 'hijau')->count(),
            'kuning' => $titikPeta->where('status', 'kuning')->count(),
            'merah' => $titikPeta->where('status', 'merah')->count(),
            'belum_ada_data' => $titikPeta->where('status', 'belum_ada_data')->count(),
        ];

        return compact('titikPeta', 'ringkasan');
    }

    /**
     * Ranking ULP berdasarkan proporsi SPKLU berstatus hijau — dipakai
     * sebagai "rekomendasi wilayah paling potensial" (halaman penuh &
     * headline ringkas di Dashboard).
     */
    public function hitungRekomendasiWilayah(): Collection
    {
        $semuaSpklu = Spklu::aktif()
            ->with('ulp')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereNotNull('ulp_mapping_id')
            ->get();

        if ($semuaSpklu->isEmpty()) {
            return collect();
        }

        $analisis = $this->analisisUtilisasi($semuaSpklu);

        return $semuaSpklu
            ->groupBy('ulp_mapping_id')
            ->map(function ($grup) use ($analisis) {
                $total = $grup->count();
                $hijau = $grup->filter(function ($spklu) use ($analisis) {
                    $status = $this->tentukanStatus(
                        $analisis['skorGabungan']->get($spklu->id),
                        $analisis['ambangBawah'],
                        $analisis['ambangAtas']
                    );

                    return $status === 'hijau';
                })->count();

                return [
                    'ulp' => $grup->first()->ulp->nama_penuh ?? 'ULP tidak diketahui',
                    'total_spklu' => $total,
                    'jumlah_hijau' => $hijau,
                    'persen_hijau' => $total > 0 ? round(($hijau / $total) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('persen_hijau')
            ->take(5)
            ->values();
    }

    /**
     * SPKLU existing berstatus merah (padat), dikelompokkan berdasarkan
     * rekomendasi tindak lanjut yang disaranin ("ganti_mesin" / "tambah_unit").
     * Dipakai buat panel terpisah di halaman Rekomendasi Lokasi, biar beda
     * jelas dengan "titik rekomendasi lokasi baru" (yang itu buat lahan
     * kosong, ini buat SPKLU yang UDAH ADA tapi kewalahan).
     *
     * @param  Collection  $titikPeta  hasil hitungZonaSpklu()['titikPeta']
     */
    public function hitungSpkluPerluTindakLanjut(Collection $titikPeta): Collection
    {
        return $titikPeta
            ->filter(fn ($t) => $t['status'] === 'merah' && $t['rekomendasi_tindak_lanjut'] !== null)
            ->sortByDesc('skor_gabungan')
            ->values();
    }

    /**
     * Bagian "mahal": scan grid koordinat, cari titik rekomendasi otomatis.
     * HANYA dipanggil dari halaman Rekomendasi Lokasi penuh — jangan
     * dipanggil dari Dashboard/widget ringkas, karena kompleksitasnya
     * O(titik_grid x jumlah_spklu).
     *
     * @param  Collection  $titikPeta  hasil hitungZonaSpklu()['titikPeta']
     */
    public function generateTitikRekomendasi(Collection $titikPeta): Collection
    {
        if ($titikPeta->count() < 2) {
            return collect();
        }

        $lats = $titikPeta->pluck('latitude');
        $lngs = $titikPeta->pluck('longitude');
        $latRata = $lats->avg();

        $bufferKm = max($titikPeta->max('radius_km'), self::RADIUS_FALLBACK_KM);
        $bufferLat = $bufferKm / 111;
        $bufferLng = $bufferKm / (111 * max(cos(deg2rad($latRata)), 0.2));

        $minLat = $lats->min() - $bufferLat;
        $maxLat = $lats->max() + $bufferLat;
        $minLng = $lngs->min() - $bufferLng;
        $maxLng = $lngs->max() + $bufferLng;

        $stepLat = self::GRID_SPASI_KM / 111;
        $stepLng = self::GRID_SPASI_KM / (111 * max(cos(deg2rad($latRata)), 0.2));

        $estimasiTitik = max(($maxLat - $minLat) / $stepLat, 1) * max(($maxLng - $minLng) / $stepLng, 1);

        if ($estimasiTitik > self::MAX_GRID_TITIK) {
            $faktor = sqrt($estimasiTitik / self::MAX_GRID_TITIK);
            $stepLat *= $faktor;
            $stepLng *= $faktor;
        }

        $kandidat = collect();

        for ($lat = $minLat; $lat <= $maxLat; $lat += $stepLat) {
            for ($lng = $minLng; $lng <= $maxLng; $lng += $stepLng) {

                $jarakTerdekat = null;
                $spkluTerdekat = null;
                $tertutupZonaMerah = false;
                $skorTertimbangTotal = 0.0;
                $bobotTotal = 0.0;

                foreach ($titikPeta as $t) {
                    $jarak = $this->jarakKm($lat, $lng, $t['latitude'], $t['longitude']);

                    if ($jarak <= $t['radius_km'] && $t['status'] === 'merah') {
                        $tertutupZonaMerah = true;
                        break;
                    }

                    if ($jarakTerdekat === null || $jarak < $jarakTerdekat) {
                        $jarakTerdekat = $jarak;
                        $spkluTerdekat = $t;
                    }

                    $radiusPengaruh = $t['radius_km'] * 2;
                    if ($jarak <= $radiusPengaruh && $t['skor_gabungan'] !== null) {
                        $bobot = 1 / (1 + $jarak);
                        $skorTertimbangTotal += ($t['skor_gabungan'] / 100) * $bobot;
                        $bobotTotal += $bobot;
                    }
                }

                if ($tertutupZonaMerah || $jarakTerdekat === null || $bobotTotal <= 0) {
                    continue;
                }

                if ($jarakTerdekat > $spkluTerdekat['radius_km'] * 2) {
                    continue;
                }

                $kandidat->push([
                    'latitude' => round($lat, 6),
                    'longitude' => round($lng, 6),
                    'skor_potensi' => round(($skorTertimbangTotal / $bobotTotal) * 100, 1),
                    'jarak_terdekat_km' => round($jarakTerdekat, 2),
                    'spklu_terdekat' => $spkluTerdekat['nama'],
                    'ulp_terdekat' => $spkluTerdekat['ulp'],
                ]);
            }
        }

        if ($kandidat->isEmpty()) {
            return collect();
        }

        $terurut = $kandidat->sortByDesc('skor_potensi')->values();
        $terpilih = collect();

        foreach ($terurut as $c) {
            $cukupJauhDariTerpilih = $terpilih->every(function ($p) use ($c) {
                return $this->jarakKm($c['latitude'], $c['longitude'], $p['latitude'], $p['longitude'])
                    >= self::JARAK_MIN_ANTAR_REKOMENDASI_KM;
            });

            if ($cukupJauhDariTerpilih) {
                $terpilih->push($c);
            }

            if ($terpilih->count() >= self::MAX_TITIK_REKOMENDASI) {
                break;
            }
        }

        return $terpilih->values();
    }

    /**
     * Rata-rata transaksi/bulan DAN rata-rata durasi/bulan (N bulan
     * terakhir), + tren 3 bulan terakhir vs 3 bulan sebelumnya (berbasis
     * jumlah transaksi), PER SPKLU.
     */
    private function hitungDataUtilisasi(Collection $spkluIds): Collection
    {
        if ($spkluIds->isEmpty()) {
            return collect();
        }

        $sejak = now()->subMonths(self::BULAN_UTILISASI)->startOfMonth();

        $bulananPerSpklu = Transaksi::query()
            ->whereIn('spklu_id', $spkluIds)
            ->where('tanggal', '>=', $sejak)
            ->selectRaw("spklu_id, DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(jumlah_transaksi) as total_jumlah, SUM(total_durasi_menit) as total_durasi")
            ->groupBy('spklu_id', 'bulan')
            ->get()
            ->groupBy('spklu_id');

        return $bulananPerSpklu->map(function ($rows) {
            $terurut = $rows->sortBy('bulan')->values();
            $rataRataJumlah = $terurut->avg('total_jumlah');
            $rataRataDurasi = $terurut->avg('total_durasi');

            $trenPersen = null;
            $trenLabel = null;

            if ($terurut->count() >= self::BULAN_TREN * 2) {
                $terakhir = $terurut->slice(-self::BULAN_TREN)->avg('total_jumlah');
                $sebelumnya = $terurut->slice(-self::BULAN_TREN * 2, self::BULAN_TREN)->avg('total_jumlah');

                if ($sebelumnya > 0) {
                    $trenPersen = round((($terakhir - $sebelumnya) / $sebelumnya) * 100, 1);
                } else {
                    $trenPersen = $terakhir > 0 ? 100.0 : 0.0;
                }

                $trenLabel = $trenPersen > 5 ? 'naik' : ($trenPersen < -5 ? 'turun' : 'stabil');
            }

            return [
                'rata_rata_jumlah' => $rataRataJumlah,
                'rata_rata_durasi' => $rataRataDurasi,
                'tren_persen' => $trenPersen,
                'tren_label' => $trenLabel,
            ];
        });
    }

    private function analisisUtilisasi(Collection $semuaSpklu): array
    {
        $ids = $semuaSpklu->pluck('id');
        $dataUtilisasi = $this->hitungDataUtilisasi($ids);

        $radiusById = $semuaSpklu->keyBy('id')->map(function (Spklu $s) {
            $radius = (float) ($s->ulp->jarak_ideal_km ?? self::RADIUS_FALLBACK_KM);

            return $radius > 0 ? $radius : self::RADIUS_FALLBACK_KM;
        });

        // Metrik frekuensi: seberapa sering dipakai per bulan, disesuaikan
        // jarak ideal ULP (biar area urban vs jarang dibandingkan adil).
        $metrikFrekuensi = $ids->mapWithKeys(function ($id) use ($dataUtilisasi, $radiusById) {
            $data = $dataUtilisasi->get($id);

            if (! $data || $data['rata_rata_jumlah'] === null) {
                return [$id => null];
            }

            $radius = $radiusById->get($id, self::RADIUS_FALLBACK_KM);

            return [$id => $data['rata_rata_jumlah'] / $radius];
        });

        // Metrik durasi: seberapa LAMA dipakai per bulan (total menit),
        // disesuaikan jarak ideal ULP dengan cara yang sama.
        $metrikDurasi = $ids->mapWithKeys(function ($id) use ($dataUtilisasi, $radiusById) {
            $data = $dataUtilisasi->get($id);

            if (! $data || $data['rata_rata_durasi'] === null) {
                return [$id => null];
            }

            $radius = $radiusById->get($id, self::RADIUS_FALLBACK_KM);

            return [$id => $data['rata_rata_durasi'] / $radius];
        });

        $skorFrekuensi = $this->normalisasiMinMax($metrikFrekuensi);
        $skorDurasi = $this->normalisasiMinMax($metrikDurasi);

        // Skor kepadatan = gabungan frekuensi & durasi. Kalau salah satu
        // datanya kosong, pakai yang ada aja (jangan sampai satu data
        // kosong bikin seluruh skor kepadatan hilang).
        $skorKepadatan = $ids->mapWithKeys(function ($id) use ($skorFrekuensi, $skorDurasi) {
            $frek = $skorFrekuensi->get($id);
            $dur = $skorDurasi->get($id);

            if ($frek === null && $dur === null) {
                return [$id => null];
            }

            if ($frek === null) {
                return [$id => $dur];
            }

            if ($dur === null) {
                return [$id => $frek];
            }

            return [$id => (self::BOBOT_FREKUENSI * $frek) + (self::BOBOT_DURASI * $dur)];
        });

        $skorTren = $ids->mapWithKeys(function ($id) use ($dataUtilisasi) {
            $tren = $dataUtilisasi->get($id)['tren_persen'] ?? null;

            if ($tren === null) {
                return [$id => null];
            }

            $clip = max(-self::TREN_CLAMP_PERSEN, min(self::TREN_CLAMP_PERSEN, $tren));

            return [$id => ($clip + self::TREN_CLAMP_PERSEN) / (self::TREN_CLAMP_PERSEN * 2)];
        });

        $skorGabungan = $ids->mapWithKeys(function ($id) use ($skorKepadatan, $skorTren) {
            $kepadatan = $skorKepadatan->get($id);

            if ($kepadatan === null) {
                return [$id => null];
            }

            $tren = $skorTren->get($id) ?? 0.5;

            return [$id => (self::BOBOT_KEPADATAN * $kepadatan) + (self::BOBOT_TREN * $tren)];
        });

        [$ambangBawah, $ambangAtas] = $this->hitungAmbangPersentil($skorGabungan);

        return compact('dataUtilisasi', 'radiusById', 'skorGabungan', 'ambangBawah', 'ambangAtas');
    }

    private function normalisasiMinMax(Collection $nilai): Collection
    {
        $terisi = $nilai->filter(fn ($v) => $v !== null);

        if ($terisi->isEmpty()) {
            return $nilai->map(fn () => null);
        }

        $min = $terisi->min();
        $max = $terisi->max();

        if ($max == $min) {
            return $nilai->map(fn ($v) => $v === null ? null : 0.5);
        }

        return $nilai->map(fn ($v) => $v === null ? null : ($v - $min) / ($max - $min));
    }

    private function hitungAmbangPersentil(Collection $skorGabungan): array
    {
        $terurut = $skorGabungan->filter(fn ($v) => $v !== null)->sort()->values();

        if ($terurut->isEmpty()) {
            return [0, 0];
        }

        return [
            $this->persentil($terurut, 33),
            $this->persentil($terurut, 66),
        ];
    }

    private function persentil(Collection $terurut, float $p): float
    {
        $n = $terurut->count();
        if ($n === 1) {
            return $terurut->first();
        }

        $index = ($p / 100) * ($n - 1);
        $bawah = (int) floor($index);
        $atas = (int) ceil($index);

        if ($bawah === $atas) {
            return $terurut[$bawah];
        }

        $pecahan = $index - $bawah;

        return $terurut[$bawah] + ($terurut[$atas] - $terurut[$bawah]) * $pecahan;
    }

    private function tentukanStatus(?float $nilaiSkor, float $ambangBawah, float $ambangAtas): string
    {
        if ($nilaiSkor === null) {
            return 'belum_ada_data';
        }

        if ($nilaiSkor <= $ambangBawah) {
            return 'hijau';
        }

        if ($nilaiSkor <= $ambangAtas) {
            return 'kuning';
        }

        return 'merah';
    }

    /**
     * Cuma dievaluasi buat status "merah". Heuristik sementara pakai
     * kapasitas kW existing sebagai proxy "masih ada ruang upgrade atau
     * enggak" — belum mempertimbangkan ketersediaan lahan fisik (data itu
     * belum ada di sistem). Kalau nanti ada data luas lahan/slot parkir
     * tersedia, ini bisa diperhalus lebih lanjut.
     */
    private function tentukanRekomendasiTindakLanjut(string $status, float $kapasitasKw): ?string
    {
        if ($status !== 'merah') {
            return null;
        }

        return $kapasitasKw < self::AMBANG_KW_GANTI_MESIN ? 'ganti_mesin' : 'tambah_unit';
    }

    private function jarakKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $radiusBumi = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radiusBumi * $c;
    }
}
