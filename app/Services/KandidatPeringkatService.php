<?php

namespace App\Services;

use App\Models\KandidatPrioritas;
use App\Models\Probabilitas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Logic ranking "Kandidat - Prioritas" (skor akhir 5-komponen), diekstrak
 * dari KandidatPeringkatController supaya bisa dipakai bareng di Dashboard
 * (Top 5 Kandidat Prioritas) tanpa duplikasi rumus.
 */
class KandidatPeringkatService
{
    public function __construct(private SpkluTerdekatService $spkluTerdekatService) {}

    private const BOBOT_PROGRES = 0.2;

    private const BOBOT_KAPASITAS = 0.2;

    private const BOBOT_DEMAND_ULP = 0.2;

    private const BOBOT_KEBUTUHAN = 0.2;

    private const BOBOT_OKUPANSI = 0.2;

    private const BOBOT_TERDEKAT = [0.5, 0.3, 0.2];

    private const CAP_KAPASITAS_KW = 200;

    private const BULAN_DEMAND_ULP = 12;

    /**
     * Hitung & urutkan seluruh kandidat (atau yang lolos filter) berdasarkan
     * skor akhir. Tidak melakukan pagination — itu tetap tanggung jawab caller.
     */
    public function rank(?string $search = null, ?int $ulpId = null): Collection
    {
        $query = KandidatPrioritas::query()
            ->with(['ulpMapping', 'probabilitas.riwayatTahapan', 'spkluTerdekat' => function ($q) {
                $q->orderBy('jarak_km');
            }])
            ->whereNotNull('koordinat');

        if ($search) {
            $query->where('nama_lokasi', 'like', "%{$search}%");
        }

        if ($ulpId) {
            $query->where('ulp_mapping_id', $ulpId);
        }

        $semuaKandidat = $query->get();

        $demandPerUlp = $this->hitungDemandPerUlp();
        $maxDemandUlp = $demandPerUlp->max() ?: 1;

        $diranking = $semuaKandidat->map(function (KandidatPrioritas $kandidat) use ($demandPerUlp, $maxDemandUlp) {

            $koordinat = $kandidat->koordinat_array;
            $tikorLengkap = ! empty($koordinat[0]) && ! empty($koordinat[1]);

            $skorProgres = $this->hitungSkorProgres($kandidat->probabilitas);
            $skorKapasitas = $this->spkluTerdekatService->hitungSkorPoinKapasitas($kandidat->probabilitas);
            $skorDemandUlp = $this->hitungSkorDemandUlp($kandidat, $demandPerUlp, $maxDemandUlp);
            $skorKebutuhan = $this->hitungSkorKebutuhan($kandidat);
            $skorPoinOkupansi = $this->hitungSkorPoinOkupansi($kandidat);

            $spkluTerdekatList = $kandidat->spkluTerdekat->take(3)->values()->map(fn ($s) => [
                'nama' => $s->nama_spklu,
                'jarak_km' => $s->jarak_km,
                'kapasitas_kw' => $s->kapasitas_kw,
                'status_jarak' => $s->status_jarak ?? '-',
            ])->all();

            if (! $tikorLengkap) {
                $skorAkhir = null;
                $statusTampil = 'TIKOR belum diisi';
            } else {
                $skorAkhir = round(
                    $skorProgres * 100 * self::BOBOT_PROGRES
                    + ($skorKapasitas ?? 0) * self::BOBOT_KAPASITAS
                    + $skorDemandUlp * self::BOBOT_DEMAND_ULP
                    + $skorKebutuhan * self::BOBOT_KEBUTUHAN
                    + $skorPoinOkupansi * self::BOBOT_OKUPANSI,
                    1
                );

                $statusTampil = match (true) {
                    $skorProgres == 0 => 'Belum ada progress',
                    $skorProgres == 1 => 'Sudah selesai/terintegrasi',
                    default => 'On Progress',
                };
            }

            KandidatPrioritas::withoutEvents(function () use ($kandidat, $skorDemandUlp, $skorKebutuhan) {
                $kandidat->newQueryWithoutScopes()
                    ->where('id', $kandidat->id)
                    ->update([
                        'demand_ulp' => round($skorDemandUlp),
                        'kebutuhan_ulp' => round($skorKebutuhan),
                    ]);
            });

            $kandidat->skor_progres_peringkat = $skorProgres;
            $kandidat->skor_kapasitas = $skorKapasitas;
            $kandidat->skor_demand_ulp = $skorDemandUlp;
            $kandidat->skor_kebutuhan = $skorKebutuhan;
            $kandidat->skor_poin_okupansi = $skorPoinOkupansi;
            $kandidat->skor_akhir = $skorAkhir;
            $kandidat->status_tampil = $statusTampil;
            $kandidat->kategori_peringkat = $this->kategoriDariSkor($skorAkhir);
            $kandidat->spklu_terdekat_list = $spkluTerdekatList;

            return $kandidat;
        });

        $terurut = $diranking->sortByDesc(fn ($k) => $k->skor_akhir ?? -1)->values();

        $rank = 1;
        foreach ($terurut as $k) {
            if (! is_null($k->skor_akhir)) {
                $k->rank = $rank++;
            }
        }

        return $terurut;
    }

    /**
     * Top N kandidat by skor akhir (skor_akhir tidak null), sudah diurutkan.
     * Dipakai Dashboard untuk "Top 5 Kandidat Prioritas".
     */
    public function top(int $n = 5): Collection
    {
        return $this->rank()
            ->filter(fn ($k) => ! is_null($k->skor_akhir))
            ->sortByDesc('skor_akhir')
            ->take($n)
            ->values();
    }

    public function ringkasan(Collection $terurut): array
    {
        $adaSkor = $terurut->filter(fn ($k) => ! is_null($k->skor_akhir));

        return [
            'total_kandidat' => $terurut->count(),
            'rata_rata_skor' => $adaSkor->isNotEmpty() ? round($adaSkor->avg('skor_akhir'), 1) : 0,
            'prioritas_tinggi' => $adaSkor->filter(fn ($k) => $k->kategori_peringkat === 'A')->count(),
            'rata_rata_progres' => $adaSkor->isNotEmpty()
                ? round($adaSkor->avg(fn ($k) => (float) $k->skor_progres_peringkat) * 100, 1)
                : 0,
            'rata_rata_kebutuhan' => $adaSkor->isNotEmpty() ? round($adaSkor->avg('skor_kebutuhan'), 1) : 0,
        ];
    }

    private function hitungSkorProgres(?Probabilitas $probabilitas): float
    {
        if (! $probabilitas) {
            return 0.0;
        }

        $badges = $probabilitas->badgePerTahap();
        $totalTahap = count(Probabilitas::TAHAPAN);

        if ($totalTahap === 0) {
            return 0.0;
        }

        $selesai = collect($badges)->filter(fn ($b) => $b['warna'] === 'hijau')->count();

        return round($selesai / $totalTahap, 4);
    }

    private function hitungSkorPoinOkupansi(KandidatPrioritas $kandidat): float
    {
        $totalPoin = ($kandidat->poin_fasilitas ?? 0)
            + ($kandidat->poin_jaringan ?? 0)
            + ($kandidat->poin_okupasi ?? 0);

        return round($totalPoin * 10, 1);
    }

    private function hitungDemandPerUlp(): Collection
    {
        $sejak = now()->subMonths(self::BULAN_DEMAND_ULP)->startOfMonth();

        $driver = DB::connection()->getDriverName();
        $dateSql = $driver === 'sqlite'
            ? "strftime('%Y-%m', transaksis.tanggal)"
            : 'DATE_FORMAT(transaksis.tanggal, "%Y-%m")';

        $rataRataPerSpklu = DB::table('transaksis')
            ->join('spklus', 'spklus.id', '=', 'transaksis.spklu_id')
            ->whereNull('spklus.deleted_at')
            ->whereNotNull('spklus.ulp_mapping_id')
            ->where('transaksis.tanggal', '>=', $sejak)
            ->selectRaw("
                spklus.id as spklu_id,
                spklus.ulp_mapping_id as ulp_mapping_id,
                {$dateSql} as bulan,
                SUM(transaksis.jumlah_transaksi) as total_bulan
            ")
            ->groupBy('spklus.id', 'spklus.ulp_mapping_id', 'bulan')
            ->get()
            ->groupBy('spklu_id')
            ->map(function ($bulanan) {
                return (object) [
                    'ulp_mapping_id' => $bulanan->first()->ulp_mapping_id,
                    'rata2_bulanan_spklu' => $bulanan->avg('total_bulan'),
                ];
            });

        return $rataRataPerSpklu
            ->groupBy('ulp_mapping_id')
            ->map(fn ($grup) => round($grup->avg('rata2_bulanan_spklu'), 1));
    }

    private function hitungSkorDemandUlp(KandidatPrioritas $kandidat, Collection $demandPerUlp, float $maxDemandUlp): float
    {
        $demandUlp = (float) ($demandPerUlp->get($kandidat->ulp_mapping_id) ?? 0);

        return round(($demandUlp / $maxDemandUlp) * 100, 1);
    }

    private function hitungSkorKebutuhan(KandidatPrioritas $kandidat): float
    {
        $jarakIdealKm = (float) ($kandidat->ulpMapping->jarak_ideal_km ?? 3);
        $jarakIdealKm = $jarakIdealKm > 0 ? $jarakIdealKm : 3;

        $terdekat = $kandidat->spkluTerdekat->take(3)->values();

        if ($terdekat->isEmpty()) {
            return 0.0;
        }

        $totalSkor = 0.0;
        $totalBobot = 0.0;

        foreach ($terdekat as $i => $spklu) {
            $bobot = self::BOBOT_TERDEKAT[$i] ?? 0;

            if ($bobot <= 0 || is_null($spklu->jarak_km)) {
                continue;
            }

            $faktorJarak = min((float) $spklu->jarak_km / $jarakIdealKm, 1) * 100 * 0.6;
            $faktorKapasitas = null;
            if (! is_null($spklu->kapasitas_kw)) {
                $faktorKapasitas = (1 - min((float) $spklu->kapasitas_kw / self::CAP_KAPASITAS_KW, 1)) * 100 * 0.4;
            } else {
                $master = DB::table('spklus')->where('nama', $spklu->nama_spklu)->first();
                if ($master && $master->kw) {
                    $faktorKapasitas = (1 - min((float) $master->kw / self::CAP_KAPASITAS_KW, 1)) * 100 * 0.4;
                }
            }
            $totalSkor += ($faktorJarak + $faktorKapasitas) * $bobot;
            $totalBobot += $bobot;
        }

        if ($totalBobot == 0) {
            return 0.0;
        }

        return round($totalSkor / $totalBobot, 1);
    }

    private function kategoriDariSkor(?float $skorAkhir): ?string
    {
        if (is_null($skorAkhir)) {
            return null;
        }

        return match (true) {
            $skorAkhir > 80 => 'A',
            $skorAkhir >= 50 => 'B',
            default => 'C',
        };
    }
}
