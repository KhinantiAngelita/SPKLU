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

        $kandidat->spkluTerdekat()->delete();

        foreach ($data['items'] as $item) {
            $kandidat->spkluTerdekat()->create([
                'nama_spklu'   => $item['nama_spklu'],
                'jarak_km'     => $item['jarak_km'],
                'status_jarak' => $this->tentukanStatusJarak($item['jarak_km'], $jarakIdealUlp),
                'sumber_jarak' => 'manual',
            ]);
        }

        $kandidat->update(['jarak_real_diisi' => true]);

        return back()->with('success', 'Jarak SPKLU terdekat berhasil disimpan.');
    }

    public function ambilOtomatis(KandidatPrioritas $kandidat)
    {
        $koordinat = $kandidat->koordinat_array;

        if (! $koordinat) {
            return back()->with('error', 'Titik koordinat (TIKOR) kandidat belum diisi.');
        }

        try {
            $hasil = $this->spkluTerdekatService->cariTerdekatViaApi($koordinat[0], $koordinat[1], 3);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal mengambil jarak otomatis dari Google. Silakan coba lagi atau isi manual.');
        }

        if (count($hasil) < 3) {
            return back()->with('error', 'Hasil valid dari Google kurang dari 3 SPKLU. Silakan isi manual.');
        }

        // Ambil standar jarak ideal ULP milik kandidat ini,
        // dipakai buat hitung ulang status_jarak dari jarak REAL (bukan hasil Haversine lama)
        $jarakIdealUlp = $kandidat->ulpMapping->jarak_ideal_km ?? null;

        $kandidat->spkluTerdekat()->delete();

        foreach ($hasil as $item) {
            $kandidat->spkluTerdekat()->create([
                'nama_spklu'   => $item['nama'],
                'jarak_km'     => $item['jarak_km'],
                'kapasitas_kw' => $item['kapasitas_kw'] ?? null,
                'status_jarak' => $this->tentukanStatusJarak($item['jarak_km'], $jarakIdealUlp),
                'sumber_jarak' => 'otomatis',
            ]);
        }

        $kandidat->update(['jarak_real_diisi' => true]);

        return back()->with('success', 'Jarak otomatis dari Google berhasil disimpan.');
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