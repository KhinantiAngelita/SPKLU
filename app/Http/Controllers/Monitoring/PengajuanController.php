<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\FsSkema;
use App\Models\Pengajuan;
use App\Services\ValidasiIntegrasiService;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function __construct(protected ValidasiIntegrasiService $validasiService) {}

    public function index(Request $request)
    {
        $pengajuans = Pengajuan::with(['fsSkema', 'pengaju', 'verifikator'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return view('monitoring.pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        // hanya FS Skema yang layak/menjadi pertimbangan & belum punya pengajuan
        $fsSkemas = FsSkema::whereDoesntHave('pengajuan')
            ->where('status_kelayakan', '!=', 'Tidak Layak')
            ->get();

        return view('monitoring.pengajuan.create', compact('fsSkemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fs_skema_id' => 'required|exists:fs_skemas,id',
        ]);

        $fsSkema = FsSkema::findOrFail($validated['fs_skema_id']);

        $pengajuan = $this->validasiService->buatPengajuan(
            $fsSkema->id,
            $fsSkema->kandidat_id,
        );

        return redirect()->route('monitoring.pengajuan.show', $pengajuan)
            ->with('success', "Pengajuan {$pengajuan->id_pengajuan} berhasil dibuat.");
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['fsSkema', 'pengaju', 'verifikator', 'jadwal']);
        return view('monitoring.pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        return view('monitoring.pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $pengajuan->update($validated);

        return redirect()->route('monitoring.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diajukan') {
            return back()->with('error', 'Hanya pengajuan berstatus "diajukan" yang bisa dihapus.');
        }

        $pengajuan->delete();

        return redirect()->route('monitoring.pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function ubahStatus(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:diverifikasi,disetujui,ditolak,tervalidasi',
            'catatan' => 'nullable|string',
        ]);

        try {
            $this->validasiService->ubahStatus($pengajuan, $validated['status'], $validated['catatan'] ?? null);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}