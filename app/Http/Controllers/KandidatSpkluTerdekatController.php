<?php

namespace App\Http\Controllers;

use App\Models\KandidatPrioritas;
use App\Models\KandidatSpkluTerdekat;
use App\Services\SpkluTerdekatService;
use Illuminate\Http\Request;

class KandidatSpkluTerdekatController extends Controller
{
     public function __construct(private SpkluTerdekatService $spkluTerdekatService)
    {
    }

    public function store(Request $request, KandidatPrioritas $kandidat)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'size:3'],
            'items.*.nama_spklu' => ['required', 'string', 'max:255'],
            'items.*.jarak_km' => ['required', 'numeric', 'min:0'],
        ]);

        $jarakIdealUlp = $kandidat->ulpMapping->jarak_ideal_km ?? null;

        $kandidat->spkluTerdekat()->delete(); // ganti 3 baris lama dengan yang baru

        foreach ($data['items'] as $item) {
            $kandidat->spkluTerdekat()->create([
                'nama_spklu'   => $item['nama_spklu'],
                'jarak_km'     => $item['jarak_km'],
                'status_jarak' => $this->tentukanStatusJarak($item['jarak_km'], $jarakIdealUlp),
                'sumber_jarak' => 'manual',
            ]);
        }

        $kandidat->update(['jarak_real_diisi' => true]); // <-- fix bug utama

        return back()->with('success', 'Jarak SPKLU terdekat berhasil disimpan.');
    }

    private function tentukanStatusJarak(float $jarakKm, ?float $jarakIdealUlp): string
    {
        if ($jarakIdealUlp === null) {
            return 'Belum Ada Standar';
        }

        return $jarakKm > $jarakIdealUlp
            ? 'Bagus'
            : 'Tidak Bagus (< ' . $jarakIdealUlp . ' km)';
    }
}