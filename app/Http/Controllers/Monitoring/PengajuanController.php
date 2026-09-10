<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Probabilitas;
use App\Models\Spklu;
use App\Models\UlpMapping;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $semua = Probabilitas::with('riwayatTahapan')->orderBy('lokasi')->get();

        $kolom = [
            'belum_mulai' => collect(),
            'on_progress' => collect(),
            'selesai_integrasi' => collect(),
        ];

        foreach ($semua as $p) {
            $kolom[$p->statusKanban()]->push($p);
        }

        return view('monitoring.pengajuan.index', [
            'belumMulai' => $kolom['belum_mulai'],
            'onProgress' => $kolom['on_progress'],
            'selesaiIntegrasi' => $kolom['selesai_integrasi'],
            'ulpList' => UlpMapping::orderBy('nama_penuh')->get(),
        ]);
    }

    /**
     * Eksekusi validasi integrasi: lokasi Probabilitas yang sudah tuntas 11 tahap
     * dipindahkan resmi menjadi record baru di Master SPKLU (status langsung "aktif",
     * sesuai keputusan sebelumnya — tanpa lewat validasi 2-tahap Super Admin lagi).
     */
    public function validasi(Request $request, Probabilitas $probabilitas)
    {
        abort_if($probabilitas->sudahDivalidasi(), 400, 'Lokasi ini sudah pernah divalidasi sebelumnya.');
        abort_unless($probabilitas->statusKanban() === 'selesai_integrasi', 400, 'Lokasi ini belum menyelesaikan tahap Integrasi.');

        $validated = $request->validate([
            'ulp_mapping_id' => 'required|exists:ulp_mappings,id',
            'type' => 'required|in:AC,DC',
            'kw' => 'required|numeric|min:0',
            'nozzle' => 'required|integer|min:1',
            'kepemilikan' => 'required|in:PLN,Swasta',
        ]);

        $nomorUrut = Spklu::withTrashed()->max('id') + 1;

        $spklu = Spklu::create([
            ...$validated,
            'id_spklu' => 'SPKLU-' . str_pad((string) $nomorUrut, 3, '0', STR_PAD_LEFT),
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

        return back()->with('success', "\"{$probabilitas->lokasi}\" berhasil divalidasi dan resmi masuk Master SPKLU.");
    }
}