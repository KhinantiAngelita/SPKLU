<?php

namespace App\Http\Controllers;

use App\Models\UlpMapping;
use App\Services\RekomendasiLokasiService;
use Illuminate\Http\Request;

/**
 * "Rekomendasi Lokasi" — sekarang jadi thin controller. Seluruh logic
 * skor gabungan (kepadatan disesuaikan jarak ideal ULP + tren) dan grid
 * scan titik rekomendasi otomatis dipindah ke RekomendasiLokasiService,
 * supaya bisa dipakai bareng Dashboard (ringkasan singkat) tanpa
 * duplikasi rumus.
 */
class RekomendasiLokasiController extends Controller
{
    public function __construct(private RekomendasiLokasiService $service)
    {
    }

    public function index(Request $request)
    {
        $ulpId = $request->input('ulp_mapping_id');

        ['titikPeta' => $titikPeta, 'ringkasan' => $ringkasan] = $this->service->hitungZonaSpklu($ulpId);

        $rekomendasiWilayah = $this->service->hitungRekomendasiWilayah();
        $titikRekomendasi = $this->service->generateTitikRekomendasi($titikPeta);

        $daftarUlp = UlpMapping::orderBy('nama_penuh')->get();

        return view('rekomendasi-lokasi.index', [
            'titikPeta' => $titikPeta,
            'ringkasan' => $ringkasan,
            'rekomendasiWilayah' => $rekomendasiWilayah,
            'titikRekomendasi' => $titikRekomendasi,
            'daftarUlp' => $daftarUlp,
            'ulpTerpilih' => $ulpId,
        ]);
    }
}