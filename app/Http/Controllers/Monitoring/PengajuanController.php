<?php

namespace App\Http\Controllers\Monitoring;

use App\Helpers\NotifikasiHelper;
use App\Http\Controllers\Controller;
use App\Models\Probabilitas;
use App\Models\Spklu;
use App\Models\UlpMapping;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userUp3 = $user?->up3;
        $isSuperAdmin = $user?->role === 'super_admin';

        $selectedUp3 = $request->get('up3');
        if (! $isSuperAdmin && $userUp3) {
            $selectedUp3 = $userUp3;
        }

        $semua = Probabilitas::with('riwayatTahapan')
            ->when($selectedUp3, fn ($q) => $q->where('up3', $selectedUp3))
            ->orderBy('lokasi')
            ->get();

        $kolom = [
            'belum_mulai' => collect(),
            'on_progress' => collect(),
            'selesai_integrasi' => collect(),
        ];

        foreach ($semua as $p) {
            $kolom[$p->statusKanban()]->push($p);
        }

        $mapKodeUnitPerUlp = Spklu::whereNotNull('kode_unit')
            ->where('kode_unit', '!=', '')
            ->when($selectedUp3, fn ($q) => $q->where('up3', $selectedUp3))
            ->get(['ulp_mapping_id', 'kode_unit'])
            ->groupBy('ulp_mapping_id')
            ->map(fn ($items) => $items->countBy('kode_unit')->sortDesc()->keys()->first())
            ->toArray();

        return view('monitoring.pengajuan.index', [
            'belumMulai' => $kolom['belum_mulai'],
            'onProgress' => $kolom['on_progress'],
            'selesaiIntegrasi' => $kolom['selesai_integrasi'],
            'ulpList' => UlpMapping::when($selectedUp3, fn ($q) => $q->where('up3', $selectedUp3))->orderBy('nama_penuh')->get(),
            'mapKodeUnitPerUlp' => $mapKodeUnitPerUlp,
            'selectedUp3' => $selectedUp3,
        ]);
    }

    /**
     * Eksekusi validasi integrasi:
     * lokasi Probabilitas yang sudah tuntas 11 tahap
     * dipindahkan resmi menjadi record baru di Master SPKLU.
     */
    public function validasi(Request $request, Probabilitas $probabilitas)
    {
        abort_if(
            $probabilitas->sudahDivalidasi(),
            400,
            'Lokasi ini sudah pernah divalidasi sebelumnya.'
        );

        abort_unless(
            $probabilitas->statusKanban() === 'selesai_integrasi',
            400,
            'Lokasi ini belum menyelesaikan tahap Integrasi.'
        );

        $validated = $request->validate([
            'ulp_mapping_id' => 'required|exists:ulp_mappings,id',
            'kode_unit' => 'nullable|string|max:50',
            'type' => 'required|in:AC,DC',
            'kw' => 'required|numeric|min:0',
            'nozzle' => 'required|integer|min:1',
            'kepemilikan' => 'required|in:PLN,Swasta',
        ]);

        if (empty($validated['kode_unit'])) {
            $validated['kode_unit'] = Spklu::where('ulp_mapping_id', $validated['ulp_mapping_id'])
                ->whereNotNull('kode_unit')
                ->where('kode_unit', '!=', '')
                ->get(['kode_unit'])
                ->countBy('kode_unit')
                ->sortDesc()
                ->keys()
                ->first();
        }

        $nomorTerakhir = Spklu::withTrashed()
            ->where('id_spklu', 'like', 'SPKLU-%')
            ->get(['id_spklu'])
            ->map(fn ($s) => (int) preg_replace('/\D/', '', $s->id_spklu))
            ->max();

        $nomorBerikutnya = ($nomorTerakhir ?? 0) + 1;

        $spklu = Spklu::create([
            ...$validated,
            'id_spklu' => 'SPKLU-'.str_pad((string) $nomorBerikutnya, 3, '0', STR_PAD_LEFT),
            'nama' => $probabilitas->lokasi,
            'latitude' => $probabilitas->tikor_lat,
            'longitude' => $probabilitas->tikor_lng,
            'status' => 'aktif',
            'sumber' => 'pengajuan',
            'tanggal_aktif' => now(),
        ]);

        $probabilitas->update([
            'spklu_id' => $spklu->id,
            'divalidasi_pada' => now(),
            'divalidasi_oleh' => $request->user()->id,
        ]);

        NotifikasiHelper::kirim(
            'pengajuan',
            "Pengajuan SPKLU \"{$probabilitas->lokasi}\" berhasil divalidasi dan resmi aktif di Master SPKLU.",
            'check-circle-2',
            route('master-spklu.index', ['search' => $spklu->nama]),
            null,
            'Pengajuan SPKLU Divalidasi'
        );

        return back()->with(
            'success',
            "\"{$probabilitas->lokasi}\" berhasil divalidasi dan resmi masuk Master SPKLU (ULP: {$spklu->ulp?->nama_penuh}, Kode Unit: {$spklu->kode_unit})."
        );
    }
}
