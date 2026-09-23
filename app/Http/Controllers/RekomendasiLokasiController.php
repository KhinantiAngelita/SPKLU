<?php

namespace App\Http\Controllers;

use App\Models\Probabilitas;
use App\Models\UlpMapping;
use App\Services\RekomendasiLokasiService;
use Illuminate\Http\Request;

/**
 * "Rekomendasi Lokasi" — sekarang jadi thin controller. Seluruh logic
 * skor gabungan (kepadatan disesuaikan jarak ideal ULP + tren, digabung
 * dengan durasi pakai) dan grid scan titik rekomendasi otomatis dipindah
 * ke RekomendasiLokasiService, supaya bisa dipakai bareng Dashboard
 * (ringkasan singkat) tanpa duplikasi rumus.
 */
class RekomendasiLokasiController extends Controller
{
    public function __construct(private RekomendasiLokasiService $service) {}

    public function index(Request $request)
    {
        $ulpId = $request->input('ulp_mapping_id');

        ['titikPeta' => $titikPeta, 'ringkasan' => $ringkasan] = $this->service->hitungZonaSpklu($ulpId);

        $rekomendasiWilayah = $this->service->hitungRekomendasiWilayah();
        $titikRekomendasi = $this->service->generateTitikRekomendasi($titikPeta);

        // SPKLU existing yang statusnya udah merah (padat) — beda dari
        // $titikRekomendasi yang isinya lahan KOSONG, ini SPKLU yang
        // UDAH ADA tapi kewalahan, jadi rekomendasinya "Ganti Mesin"
        // atau "Tambah Unit", bukan "buka lokasi baru".
        $spkluPerluTindakLanjut = $this->service->hitungSpkluPerluTindakLanjut($titikPeta);

        // SPKLU Baru (Kandidat Aktif di Pipeline Probing yang punya koordinat)
        $kandidatBaruQuery = Probabilitas::whereNotNull('tikor_lat')
            ->whereNotNull('tikor_lng')
            ->whereNull('spklu_id')
            ->with(['riwayatTahapan']);

        if ($ulpId) {
            $ulpModel = UlpMapping::find($ulpId);
            if ($ulpModel) {
                $kandidatBaruQuery->where(function ($q) use ($ulpModel) {
                    $q->where('ulp', $ulpModel->nama_singkat)
                        ->orWhere('ulp', $ulpModel->nama_penuh);
                });
            }
        }

        $kandidatBaru = $kandidatBaruQuery->get()->map(function ($k) {
            return [
                'id' => $k->id,
                'nama' => $k->lokasi,
                'ulp' => $k->ulp,
                'latitude' => (float) $k->tikor_lat,
                'longitude' => (float) $k->tikor_lng,
                'tahap' => $k->tahapSaatIni(),
                'status_kanban' => $k->statusKanban(),
                'pic' => $k->pic,
                'mitra_mesin' => $k->mitra_mesin,
            ];
        })->values();

        $ringkasan['kandidat_baru'] = $kandidatBaru->count();
        $ringkasan['kandidat_ada_pasangan'] = $kandidatBaru->filter(fn ($k) => ! empty(trim($k['mitra_mesin'] ?? '')))->count();
        $ringkasan['kandidat_belum_pasangan'] = $kandidatBaru->count() - $ringkasan['kandidat_ada_pasangan'];
        $ringkasan['dc'] = collect($titikPeta)->where('type', 'DC')->count();
        $ringkasan['ac'] = collect($titikPeta)->where('type', 'AC')->count();

        $daftarUlp = UlpMapping::orderBy('nama_penuh')->get();

        return view('rekomendasi-lokasi.index', [
            'titikPeta' => $titikPeta,
            'ringkasan' => $ringkasan,
            'rekomendasiWilayah' => $rekomendasiWilayah,
            'titikRekomendasi' => $titikRekomendasi,
            'spkluPerluTindakLanjut' => $spkluPerluTindakLanjut,
            'kandidatBaru' => $kandidatBaru,
            'daftarUlp' => $daftarUlp,
            'ulpTerpilih' => $ulpId,
        ]);
    }
}
