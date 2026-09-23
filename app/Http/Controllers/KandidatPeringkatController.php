<?php

namespace App\Http\Controllers;

use App\Models\UlpMapping;
use App\Services\KandidatPeringkatService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Controller untuk halaman "Kandidat Peringkat" — sekarang jadi thin
 * controller. Logic scoring (5 komponen: Progres, Kapasitas, Demand ULP,
 * Kebutuhan, Poin Okupansi/Fasilitas/Jaringan) dipindah ke
 * KandidatPeringkatService supaya bisa dipakai bareng di Dashboard
 * ("Top 5 Kandidat Prioritas") tanpa duplikasi rumus.
 */
class KandidatPeringkatController extends Controller
{
    public function __construct(private KandidatPeringkatService $peringkatService) {}

    public function index2(Request $request)
    {
        $search = $request->input('search');
        $ulpId = $request->input('ulp_mapping_id');

        $terurut = $this->peringkatService->rank($search, $ulpId);
        $ringkasan = $this->peringkatService->ringkasan($terurut);

        $perPage = 15;
        $halaman = (int) $request->input('page', 1);

        $kandidatList = new LengthAwarePaginator(
            $terurut->forPage($halaman, $perPage)->values(),
            $terurut->count(),
            $perPage,
            $halaman,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $daftarUlp = UlpMapping::orderBy('nama_penuh')->get();

        return view('kandidat-prioritas.index2', [
            'kandidatList' => $kandidatList,
            'ringkasan' => $ringkasan,
            'daftarUlp' => $daftarUlp,
            'filter' => ['search' => $search, 'ulpId' => $ulpId],
        ]);
    }
}
