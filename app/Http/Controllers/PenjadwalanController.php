<?php

namespace App\Http\Controllers;

use App\Helpers\NotifikasiHelper;
use App\Models\Jadwal;
use App\Models\Probabilitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PenjadwalanController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with(['probabilitas', 'penanggungJawab'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderBy('waktu_mulai')
            ->paginate(10)
            ->withQueryString();

        // Data kalender bulan berjalan — dipindah kesini dari create(), supaya
        // kalender & "Jadwal Hari Ini" tampil di halaman Daftar Jadwal
        // (di atas tabel), bukan lagi di form Buat Jadwal.
        $bulanTampil = $request->bulan
            ? Carbon::parse($request->bulan)
            : now();

        $jadwalSebulan = Jadwal::with('probabilitas')
            ->whereBetween('waktu_mulai', [
                $bulanTampil->copy()->startOfMonth(),
                $bulanTampil->copy()->endOfMonth(),
            ])
            ->orderBy('waktu_mulai')
            ->get()
            ->map(fn ($j) => [
                'id' => $j->id,
                'tanggal' => $j->waktu_mulai->format('Y-m-d'),
                'jam' => $j->waktu_mulai->format('H:i'),
                'lokasi_nama' => $j->probabilitas->lokasi ?? 'Lokasi',
                'mode' => $j->mode,
                'deskripsi' => $j->deskripsi,
            ]);

        return view('penjadwalan.index', compact('jadwals', 'jadwalSebulan', 'bulanTampil'));
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

        $jadwal = Jadwal::create([
            ...$validated,
            'judul' => $this->buatJudulOtomatis($probabilitas, $validated['mode']),
            'status' => 'terjadwal',
            'dibuat_oleh' => auth()->id(),
        ]);

        NotifikasiHelper::kirim(
            'jadwal',
            "Agenda pertemuan baru \"{$jadwal->judul}\" dijadwalkan pada ".Carbon::parse($jadwal->waktu_mulai)->translatedFormat('d M Y H:i').'.',
            'calendar-check',
            route('penjadwalan.index'),
            ['super_admin', 'pemasaran', 'pengelola'],
            'Agenda Pertemuan Baru'
        );

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

        NotifikasiHelper::kirim(
            'jadwal',
            "Jadwal pertemuan \"{$jadwal->judul}\" telah diperbarui (Status: {$jadwal->status}).",
            'calendar',
            route('penjadwalan.index'),
            ['super_admin', 'pemasaran', 'pengelola'],
            'Pembaruan Jadwal Pertemuan'
        );

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
            'platform' => 'nullable|in:Zoom,Google Meet,Lainnya|required_if:mode,online',
            'link_pertemuan' => 'nullable|url|required_if:mode,online',
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
