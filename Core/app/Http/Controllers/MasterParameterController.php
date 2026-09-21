<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogHelper;
use App\Models\PoinKesiapanJaringan;
use App\Models\TargetTahunan;
use App\Models\TarifListrik;
use Illuminate\Http\Request;

class MasterParameterController extends Controller
{
    public function index()
    {
        return view('master-parameter.index', [
            'tarifListrik' => TarifListrik::all(),
            'poinJaringan' => PoinKesiapanJaringan::orderBy('urutan')->get(),
            'targetTahunan' => TargetTahunan::orderByDesc('tahun')->get(),
        ]);
    }

    public function updateTarif(Request $request, TarifListrik $tarif)
    {
        $request->validate(['tarif_per_kwh' => 'required|numeric|min:0']);

        // Ambil nilai LAMA dulu sebelum diubah — setelah update(), getOriginal()
        // sudah ke-sync ke nilai baru, jadi harus ditangkap di sini.
        $nilaiLama = $tarif->only(['tarif_per_kwh']);

        $tarif->update([
            'tarif_per_kwh' => $request->tarif_per_kwh,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($tarif, 'updated', $request->user(), $nilaiLama, $tarif->only(['tarif_per_kwh']));

        return back()->with('success', "Tarif {$tarif->kode} berhasil diperbarui.");
    }

    public function updatePoinJaringan(Request $request, PoinKesiapanJaringan $poin)
    {
        $request->validate(['poin' => 'required|integer|min:0|max:20']);

        $nilaiLama = $poin->only(['poin']);

        $poin->update([
            'poin' => $request->poin,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($poin, 'updated', $request->user(), $nilaiLama, $poin->only(['poin']));

        return back()->with('success', 'Poin kesiapan jaringan berhasil diperbarui.');
    }

    public function storeTarget(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|unique:target_tahunan,tahun',
            'target_jumlah_spklu' => 'required|integer|min:0',
        ]);

        $target = TargetTahunan::create([
            ...$request->only('tahun', 'target_jumlah_spklu'),
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($target, 'created', $request->user(), [], $target->only(['tahun', 'target_jumlah_spklu']));

        return back()->with('success', 'Target tahunan berhasil ditambahkan.');
    }
}