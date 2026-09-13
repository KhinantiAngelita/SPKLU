<?php

namespace App\Http\Controllers;

use App\Models\KandidatPrioritas;
use App\Models\Probabilitas;
use App\Models\UlpMapping;
use App\Services\SpkluTerdekatService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk halaman "Kandidat Peringkat" — sesuai tab Excel
 * "Kandidat - Prioritas" (BUKAN tab "Kandidat Baru - Jarak & Poin" yang
 * sudah ada di KandidatPrioritasController).
 *
 * Rumus Skor Akhir (v2 — DIPERLUAS dari Excel atas permintaan user, sudah
 * TIDAK sama persis dengan formula asli Excel AI5 yang cuma 3 komponen):
 *
 *      Skor Akhir = 20% Skor Progres + 20% Skor Kapasitas + 20% Skor Demand ULP
 *                   + 20% Skor Kebutuhan + 20% Skor Poin Okupansi/Fasilitas/Jaringan
 *
 * - Skor Progres     : % tahapan probing yang sudah "berhasil", dari relasi
 *                       Probabilitas::badgePerTahap().
 * - Skor Kapasitas   : SpkluTerdekatService::hitungSkorPoinKapasitas() — sama
 *                       persis dengan yang dipakai di halaman "Jarak & Poin",
 *                       supaya definisinya konsisten di seluruh sistem.
 * - Skor Demand ULP  : rata-rata transaksi bulanan PER SPKLU, dirata-rata lagi
 *                       antar SPKLU dalam satu ULP.
 * - Skor Kebutuhan   : dibanding 3 SPKLU existing terdekat (jarak & kapasitas),
 *                       dibobot 50% / 30% / 20%.
 * - Skor Poin Okupansi/Fasilitas/Jaringan: (poin_fasilitas + poin_jaringan +
 *                       poin_okupasi) * 10 — sesuai kolom AH di Excel (dulu
 *                       cuma info, sekarang ikut masuk rumus atas permintaan user.
 *
 * Halaman ini TIDAK menulis ke kolom `skor_prioritas` (itu punya
 * KandidatPrioritasController / halaman "Jarak & Poin"). Kolom `demand_ulp`
 * dan `kebutuhan_ulp` di-refresh di sini karena memang kolom itu yang
 * disiapkan untuk rumus ini.
 */
class KandidatPeringkatController extends Controller
{
    public function __construct(private SpkluTerdekatService $spkluTerdekatService)
    {
    }

    private const BOBOT_PROGRES    = 0.2;
    private const BOBOT_KAPASITAS  = 0.2;
    private const BOBOT_DEMAND_ULP = 0.2;
    private const BOBOT_KEBUTUHAN  = 0.2;
    private const BOBOT_OKUPANSI   = 0.2;

    /** Bobot SPKLU terdekat #1/#2/#3 di Skor Kebutuhan */
    private const BOBOT_TERDEKAT = [0.5, 0.3, 0.2];

    /** Cap kapasitas (kW) untuk normalisasi faktor kapasitas di Skor Kebutuhan */
    private const CAP_KAPASITAS_KW = 200;

    /** Demand ULP dihitung dari rata-rata transaksi bulanan berapa bulan ke belakang */
    private const BULAN_DEMAND_ULP = 12;

    public function index2(Request $request)
    {
        $search = $request->input('search');
        $ulpId  = $request->input('ulp_mapping_id');

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

        // Demand tiap ULP = rata-rata (rata2 transaksi bulanan per SPKLU) dari
        // seluruh SPKLU existing di ULP itu — bukan total/SUM ULP.
        $demandPerUlp = $this->hitungDemandPerUlp();
        $maxDemandUlp = $demandPerUlp->max() ?: 1;

        $diranking = $semuaKandidat->map(function (KandidatPrioritas $kandidat) use ($demandPerUlp, $maxDemandUlp) {

            $koordinat    = $kandidat->koordinat_array;
            $tikorLengkap = ! empty($koordinat[0]) && ! empty($koordinat[1]);

            $skorProgres      = $this->hitungSkorProgres($kandidat->probabilitas);
            $skorKapasitas    = $this->spkluTerdekatService->hitungSkorPoinKapasitas($kandidat->probabilitas);
            $skorDemandUlp    = $this->hitungSkorDemandUlp($kandidat, $demandPerUlp, $maxDemandUlp);
            $skorKebutuhan    = $this->hitungSkorKebutuhan($kandidat);
            $skorPoinOkupansi = $this->hitungSkorPoinOkupansi($kandidat);

            $spkluTerdekatList = $kandidat->spkluTerdekat->take(3)->values()->map(fn ($s) => [
                'nama'         => $s->nama_spklu,
                'jarak_km'     => $s->jarak_km,
                'kapasitas_kw' => $s->kapasitas_kw,
                'status_jarak' => $s->status_jarak ?? '-',
            ])->all();

            if (! $tikorLengkap) {
                $skorAkhir    = null;
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
                    default            => 'On Progress',
                };
            }

            KandidatPrioritas::withoutEvents(function () use ($kandidat, $skorDemandUlp, $skorKebutuhan) {
                $kandidat->newQueryWithoutScopes()
                    ->where('id', $kandidat->id)
                    ->update([
                        'demand_ulp'    => round($skorDemandUlp),
                        'kebutuhan_ulp' => round($skorKebutuhan),
                    ]);
            });

            // Properti display-only. Nama SENGAJA dibedakan dari attribute
            // asli di model supaya TIDAK ketutup accessor bawaan:
            // - skor_progres_peringkat (bukan skor_progres)
            // - kategori_peringkat (bukan kategori)
            $kandidat->skor_progres_peringkat = $skorProgres;
            $kandidat->skor_kapasitas         = $skorKapasitas;
            $kandidat->skor_demand_ulp        = $skorDemandUlp;
            $kandidat->skor_kebutuhan         = $skorKebutuhan;
            $kandidat->skor_poin_okupansi     = $skorPoinOkupansi;
            $kandidat->skor_akhir             = $skorAkhir;
            $kandidat->status_tampil          = $statusTampil;
            $kandidat->kategori_peringkat     = $this->kategoriDariSkor($skorAkhir);
            $kandidat->spklu_terdekat_list     = $spkluTerdekatList;

            return $kandidat;
        });

        // Rank dihitung dari seluruh kandidat yang lolos filter, bukan per halaman.
        $terurut = $diranking->sortByDesc(fn ($k) => $k->skor_akhir ?? -1)->values();

        $rank = 1;
        foreach ($terurut as $k) {
            if (! is_null($k->skor_akhir)) {
                $k->rank = $rank++;
            }
        }

        $ringkasan = $this->hitungRingkasan($terurut);

        $perPage = 15;
        $halaman = (int) $request->input('page', 1);

        $kandidatList = new \Illuminate\Pagination\LengthAwarePaginator(
            $terurut->forPage($halaman, $perPage)->values(),
            $terurut->count(),
            $perPage,
            $halaman,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $daftarUlp = UlpMapping::orderBy('nama_penuh')->get();

        return view('kandidat-prioritas.index2', [
            'kandidatList' => $kandidatList,
            'ringkasan'    => $ringkasan,
            'daftarUlp'    => $daftarUlp,
            'filter'       => ['search' => $search, 'ulpId' => $ulpId],
        ]);
    }

    /**
     * Skor Progres (0-1): proporsi tahapan probing yang sudah "berhasil"
     * (badge hijau) dari total tahapan resmi (Probabilitas::TAHAPAN).
     */
    private function hitungSkorProgres(?Probabilitas $probabilitas): float
    {
        if (! $probabilitas) {
            return 0.0;
        }

        $badges     = $probabilitas->badgePerTahap();
        $totalTahap = count(Probabilitas::TAHAPAN);

        if ($totalTahap === 0) {
            return 0.0;
        }

        $selesai = collect($badges)->filter(fn ($b) => $b['warna'] === 'hijau')->count();

        return round($selesai / $totalTahap, 4);
    }

    /**
     * Skor Poin Okupansi, Fasilitas, & Perluasan Jaringan (0-100): sesuai
     * kolom AH di Excel (AG*10), AG = Poin Fasilitas + Jaringan + Okupansi.
     */
    private function hitungSkorPoinOkupansi(KandidatPrioritas $kandidat): float
    {
        $totalPoin = ($kandidat->poin_fasilitas ?? 0)
            + ($kandidat->poin_jaringan ?? 0)
            + ($kandidat->poin_okupasi ?? 0);

        return round($totalPoin * 10, 1);
    }

    /**
     * Rata-rata transaksi bulanan (N bulan terakhir) PER SPKLU, lalu
     * dirata-rata lagi antar SPKLU dalam satu ULP — sesuai 'Ringkasan per
     * ULP'!C di Excel, BUKAN total/SUM seluruh SPKLU di ULP.
     */
    private function hitungDemandPerUlp(): Collection
    {
        $sejak = now()->subMonths(self::BULAN_DEMAND_ULP)->startOfMonth();

        $rataRataPerSpklu = DB::table('transaksis')
            ->join('spklus', 'spklus.id', '=', 'transaksis.spklu_id')
            ->whereNull('spklus.deleted_at')
            ->whereNotNull('spklus.ulp_mapping_id')
            ->where('transaksis.tanggal', '>=', $sejak)
            ->selectRaw('
                spklus.id as spklu_id,
                spklus.ulp_mapping_id as ulp_mapping_id,
                DATE_FORMAT(transaksis.tanggal, "%Y-%m") as bulan,
                SUM(transaksis.jumlah_transaksi) as total_bulan
            ')
            ->groupBy('spklus.id', 'spklus.ulp_mapping_id', 'bulan')
            ->get()
            ->groupBy('spklu_id')
            ->map(function ($bulanan) {
                return (object) [
                    'ulp_mapping_id'      => $bulanan->first()->ulp_mapping_id,
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

    /**
     * Skor Kebutuhan (0-100): dibanding 3 SPKLU existing terdekat, dibobot
     * 50% / 30% / 20%.
     */
    private function hitungSkorKebutuhan(KandidatPrioritas $kandidat): float
    {
        $jarakIdealKm = (float) ($kandidat->ulpMapping->jarak_ideal_km ?? 3);
        $jarakIdealKm = $jarakIdealKm > 0 ? $jarakIdealKm : 3;

        $terdekat = $kandidat->spkluTerdekat->take(3)->values();

        if ($terdekat->isEmpty()) {
            return 0.0;
        }

        $totalSkor  = 0.0;
        $totalBobot = 0.0;

        foreach ($terdekat as $i => $spklu) {
            $bobot = self::BOBOT_TERDEKAT[$i] ?? 0;

            if ($bobot <= 0 || is_null($spklu->jarak_km)) {
                continue;
            }

            $faktorJarak     = min((float) $spklu->jarak_km / $jarakIdealKm, 1) * 100 * 0.6;
            $faktorKapasitas = null;
                if (! is_null($spklu->kapasitas_kw)) {
                    $faktorKapasitas = (1 - min((float) $spklu->kapasitas_kw / self::CAP_KAPASITAS_KW, 1)) * 100 * 0.4;
                } else {
                    $master = DB::table('spklus')->where('nama', $spklu->nama_spklu)->first();
                    if ($master && $master->kw) {
                        $faktorKapasitas = (1 - min((float) $master->kw / self::CAP_KAPASITAS_KW, 1)) * 100 * 0.4;
                    }
                }
            $totalSkor  += ($faktorJarak + $faktorKapasitas) * $bobot;
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
            $skorAkhir > 80  => 'A',
            $skorAkhir >= 50 => 'B',
            default          => 'C',
        };
    }

    private function hitungRingkasan(Collection $terurut): array
    {
        $adaSkor = $terurut->filter(fn ($k) => ! is_null($k->skor_akhir));

        return [
            'total_kandidat'      => $terurut->count(),
            'rata_rata_skor'      => $adaSkor->isNotEmpty() ? round($adaSkor->avg('skor_akhir'), 1) : 0,
            'prioritas_tinggi'    => $adaSkor->filter(fn ($k) => $k->kategori_peringkat === 'A')->count(),
            'rata_rata_progres'   => $adaSkor->isNotEmpty()
                ? round($adaSkor->avg(fn ($k) => (float) $k->skor_progres_peringkat) * 100, 1)
                : 0,
            'rata_rata_kebutuhan' => $adaSkor->isNotEmpty() ? round($adaSkor->avg('skor_kebutuhan'), 1) : 0,
        ];
    }
}