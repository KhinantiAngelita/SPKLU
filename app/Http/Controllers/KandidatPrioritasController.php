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

        $query = KandidatPrioritas::query()->with('ulpMapping');

        if ($search) $query->where('nama_lokasi', 'like', "%{$search}%");
        if ($ulpId) $query->where('ulp_mapping_id', $ulpId);
        if ($typeKw) $query->where('type_kw', $typeKw);
        if ($kepemilikan) $query->where('kepemilikan', $kepemilikan);

        $kandidatList = $query->orderByDesc('skor_prioritas')->paginate(15)->withQueryString();

        // "3 SPKLU Terdekat" dihitung on-the-fly per baris dari Master SPKLU
        $kandidatList->getCollection()->transform(function (KandidatPrioritas $kandidat) {
            $koordinat = $kandidat->koordinat_array;

            $kandidat->spklu_terdekat = $koordinat
                ? $this->spkluTerdekatService->cariTerdekat($koordinat[0], $koordinat[1], 3)
                : [];

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