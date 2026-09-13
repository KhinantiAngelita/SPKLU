<?php

namespace App\Http\Controllers;

use App\Models\KandidatPrioritas;
use App\Models\UlpMapping;
use App\Services\SpkluTerdekatService;
use Illuminate\Http\Request;

class KandidatPrioritasController extends Controller
{
    public function __construct(private SpkluTerdekatService $spkluTerdekatService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $ulpId = $request->input('ulp_mapping_id');
        $typeKw = $request->input('type_kw');
        $kepemilikan = $request->input('kepemilikan');

        $query = KandidatPrioritas::query()->with(['ulpMapping', 'probabilitas']);

        if ($search) $query->where('nama_lokasi', 'like', "%{$search}%");
        if ($ulpId) $query->where('ulp_mapping_id', $ulpId);
        if ($typeKw) $query->where('type_kw', $typeKw);
        if ($kepemilikan) $query->where('kepemilikan', $kepemilikan);

        $kandidatList = $query->orderByDesc('skor_prioritas')->paginate(15)->withQueryString();

        $kandidatList->getCollection()->transform(function (KandidatPrioritas $kandidat) {
        $koordinat = $kandidat->koordinat_array;

        $ulp = $kandidat->ulpMapping;
        $jarakIdealUlp = $ulp->jarak_ideal_km ?? null;

        // Ambil data manual dulu (yang diinput via modal)
        $manualList = $kandidat->spkluTerdekat()->orderBy('jarak_km')->get();

        if ($manualList->isNotEmpty()) {
            $spkluTerdekat = $manualList->map(fn ($m) => [
                'nama'         => $m->nama_spklu,
                'jarak_km'     => $m->jarak_km,
                'status_jarak' => $m->status_jarak ?? '-',
            ])->all();

            $jarakRealList = $manualList->pluck('jarak_km')->all();
        } else {
            $spkluTerdekat = $koordinat
                ? $this->spkluTerdekatService->cariTerdekat($koordinat[0], $koordinat[1], 3)
                : [];

            $jarakRealList = collect($spkluTerdekat)->pluck('jarak_km')->all();
        }

        $skorJarak = $this->spkluTerdekatService->hitungSkorJarak($jarakRealList, $jarakIdealUlp);
        $skorPoinKapasitas = $this->spkluTerdekatService->hitungSkorPoinKapasitas($kandidat->probabilitas);
        $skorPoinOkupansi = $this->spkluTerdekatService->hitungSkorPoinOkupansi(
            $kandidat->poin_fasilitas ?? 0,
            $kandidat->poin_jaringan ?? 0,
            $kandidat->poin_okupasi ?? 0
        );
        $skorPrioritasAkhir = $this->spkluTerdekatService->hitungSkorPrioritasAkhir(
            $skorJarak,
            $skorPoinKapasitas,
            $skorPoinOkupansi
        );

        // ==== SIMPAN DULU, SEBELUM nempel attribute display-only ====
        if ($skorPrioritasAkhir !== null && $kandidat->skor_prioritas != $skorPrioritasAkhir) {
            KandidatPrioritas::withoutEvents(function () use ($kandidat, $skorPrioritasAkhir) {
                $kandidat->newQueryWithoutScopes()
                    ->where('id', $kandidat->id)
                    ->update(['skor_prioritas' => $skorPrioritasAkhir]);
            });
        }

        // ==== BARU nempel data virtual/display setelah save ====
        $kandidat->spklu_terdekat = $spkluTerdekat;
        $kandidat->skor_jarak = $skorJarak;
        $kandidat->skor_poin_kapasitas = $skorPoinKapasitas;
        $kandidat->skor_poin_okupansi = $skorPoinOkupansi;
        $kandidat->skor_prioritas_akhir = $skorPrioritasAkhir;
        $kandidat->skor_prioritas = $skorPrioritasAkhir; // biar konsisten tampil di request ini

        return $kandidat;
    });

        // Summary cards dihitung dari SELURUH data yang lolos filter
        $baseQuery = fn () => KandidatPrioritas::query()
            ->when($search, fn ($q) => $q->where('nama_lokasi', 'like', "%{$search}%"))
            ->when($ulpId, fn ($q) => $q->where('ulp_mapping_id', $ulpId))
            ->when($typeKw, fn ($q) => $q->where('type_kw', $typeKw))
            ->when($kepemilikan, fn ($q) => $q->where('kepemilikan', $kepemilikan));

        $totalKandidat = $baseQuery()->count();
        $rataRataSkor = $baseQuery()->avg('skor_prioritas');
        $butuhPerhatian = $baseQuery()->where('jarak_real_diisi', false)->count();

        // TODO: demand_ulp & kebutuhan_ulp adalah kolom rumus LAMA (30/30/40) yang sudah
        // tidak dipakai lagi. Belum diganti ke rata-rata Skor Jarak / Skor Poin Kapasitas /
        // Skor Poin Okupansi karena belum dikonfirmasi — lihat pesan sebelumnya.
        $rataRataDemand = $baseQuery()->avg('demand_ulp');
        $rataRataKebutuhan = $baseQuery()->avg('kebutuhan_ulp');

        $ringkasan = [
            'total_kandidat' => $totalKandidat,
            'rata_rata_skor' => $rataRataSkor ? (int) round($rataRataSkor) : 0,
            'butuh_perhatian' => $butuhPerhatian,
            'demand_ulp' => $rataRataDemand ? (int) round($rataRataDemand) : 0,
            'kebutuhan_ulp' => $rataRataKebutuhan ? (int) round($rataRataKebutuhan) : 0,
        ];

        $daftarUlp = UlpMapping::orderBy('nama_penuh')->get();
        $daftarTypeKw = KandidatPrioritas::query()->whereNotNull('type_kw')->distinct()->orderBy('type_kw')->pluck('type_kw');
        $daftarKepemilikan = KandidatPrioritas::query()->whereNotNull('kepemilikan')->distinct()->orderBy('kepemilikan')->pluck('kepemilikan');

        return view('kandidat-prioritas.index', [
            'kandidatList' => $kandidatList,
            'ringkasan' => $ringkasan,
            'daftarUlp' => $daftarUlp,
            'daftarTypeKw' => $daftarTypeKw,
            'daftarKepemilikan' => $daftarKepemilikan,
            'filter' => compact('search', 'ulpId', 'typeKw', 'kepemilikan'),
        ]);
    }
}