<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with(['pengajuan.fsSkema', 'pj'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('tanggal_pelaksanaan')
            ->paginate(10);

        return view('penjadwalan.index', compact('jadwals'));
    }

    public function create()
    {
        // hanya pengajuan yang sudah disetujui/tervalidasi yang boleh dijadwalkan
        $pengajuans = Pengajuan::whereIn('status', ['disetujui', 'tervalidasi'])
            ->with('fsSkema')
            ->get();
        $users = User::orderBy('name')->get();

        return view('penjadwalan.create', compact('pengajuans', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        Jadwal::create([
            ...$validated,
            'status' => 'terjadwal',
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('penjadwalan.index')->with('success', 'Jadwal berhasil dibuat.');
    }


    public function edit(Jadwal $jadwal)
    {
        $pengajuans = Pengajuan::whereIn('status', ['disetujui', 'tervalidasi'])->with('fsSkema')->get();
        $users = User::orderBy('name')->get();

        return view('penjadwalan.edit', compact('jadwal', 'pengajuans', 'users'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $this->validasi($request);
        $validated['status'] = $request->input('status', $jadwal->status);

        $jadwal->update($validated);

        return redirect()->route('penjadwalan.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('penjadwalan.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    protected function validasi(Request $request): array
    {
        return $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_mulai' => 'required|date',
            'mode' => 'required|in:online,offline',
            'lokasi' => 'nullable|string|required_if:mode,offline',
            'penanggung_jawab' => 'nullable|exists:users,id',
        ]);
    }
}