<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Probabilitas;
use App\Models\User;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with(['probabilitas', 'penanggungJawab'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('waktu_mulai')
            ->paginate(10)
            ->withQueryString();

        return view('penjadwalan.index', compact('jadwals'));
    }

    public function create()
    {
        $probabilitasList = $this->probabilitasBisaDijadwalkan();
        $users = User::orderBy('name')->get();

        return view('penjadwalan.create', compact('probabilitasList', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);
        $probabilitas = Probabilitas::find($validated['probabilitas_id']);

        Jadwal::create([
            ...$validated,
            'judul' => $this->buatJudulOtomatis($probabilitas, $validated['mode']),
            'status' => 'terjadwal',
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('penjadwalan.index')->with('success', 'Jadwal berhasil dibuat.');
    }

    public function edit(Jadwal $jadwal)
    {
        $probabilitasList = $this->probabilitasBisaDijadwalkan();

        if ($jadwal->probabilitas_id && ! $probabilitasList->contains('id', $jadwal->probabilitas_id)) {
            $lokasiSaatIni = Probabilitas::find($jadwal->probabilitas_id);
            if ($lokasiSaatIni) {
                $probabilitasList->push($lokasiSaatIni);
            }
        }

        $users = User::orderBy('name')->get();

        return view('penjadwalan.edit', compact('jadwal', 'probabilitasList', 'users'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $this->validasi($request);
        $validated['status'] = $request->input('status', $jadwal->status);

        $probabilitas = Probabilitas::find($validated['probabilitas_id']);
        $validated['judul'] = $this->buatJudulOtomatis($probabilitas, $validated['mode']);

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
            'probabilitas_id' => 'required|exists:probabilitas,id',
            'waktu_mulai' => 'required|date',
            'mode' => 'required|in:online,offline',
            'lokasi' => 'nullable|string|required_if:mode,offline',
            'penanggung_jawab' => 'nullable|exists:users,id',
        ]);
    }

    protected function buatJudulOtomatis(?Probabilitas $probabilitas, string $mode): string
    {
        $namaLokasi = $probabilitas?->lokasi ?? 'Lokasi';
        $labelMode = $mode === 'online' ? 'Online' : 'Kunjungan Lapangan';

        return "{$labelMode} — {$namaLokasi}";
    }

    protected function probabilitasBisaDijadwalkan()
    {
        return Probabilitas::with('riwayatTahapan')
            ->orderBy('lokasi')
            ->get()
            ->reject(fn ($p) => $p->statusKanban() === 'selesai_integrasi')
            ->values();
    }
}