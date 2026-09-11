<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Probabilitas;
use App\Models\TahapanProbing;
use App\Services\ProbabilitasScoreService;
use Illuminate\Http\Request;

class ProbabilitasController extends Controller
{
    public function __construct(
        protected ProbabilitasScoreService $scoreService,
        protected \App\Services\KandidatPrioritasSyncService $kandidatSync,
    ){
    }

    /**
     * Grid ringkasan (Image 1). Eager-load riwayat tahapan supaya
     * badgePerTahap() di view tidak N+1.
     */
    public function index(Request $request)
    {
        $query = Probabilitas::with('riwayatTahapan')->latest();

        if ($request->filled('ulp')) {
            $query->where('ulp', $request->input('ulp'));
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }
        if ($request->filled('search')) {
            $query->where('lokasi', 'like', '%' . $request->input('search') . '%');
        }

        $daftarProbabilitas = $query->paginate(15);

        $daftarUlp = \App\Models\UlpMapping::orderBy('nama_penuh')->get();

        return view('monitoring.probabilitas.index', compact('daftarProbabilitas', 'daftarUlp'));
    }

    /**
     * Halaman form "Tambah Kandidat Baru".
     */
    public function create()
    {
        $daftarUlp = \App\Models\UlpMapping::orderBy('nama_penuh')->get();

        return view('monitoring.kandidat.create', compact('daftarUlp'));
    }

    /**
     * Simpan kandidat baru — identitas dasar + kontak (Nama, Alamat,
     * No Telp, PIC, Titik Koordinat). Field penilaian (fasilitas,
     * okupansi, kebutuhan mesin, dst) diisi belakangan lewat update().
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lokasi'        => 'required|string|max:255',
            'alamat'        => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:30',
            'pic'           => 'nullable|string|max:255',
            'tikor'         => ['required', 'regex:/^-?\d{1,3}(\.\d+)?\s*,\s*-?\d{1,3}(\.\d+)?$/'],
        ], [
            'tikor.regex' => 'Format titik koordinat harus "latitude, longitude", contoh: -6.597147, 106.806039',
        ]);

        [$lat, $lng] = array_map('trim', explode(',', $validated['tikor']));

        request()->merge(['tikor_lat' => $lat, 'tikor_lng' => $lng]);
        $request->validate([
            'tikor_lat' => 'numeric|between:-90,90',
            'tikor_lng' => 'numeric|between:-180,180',
        ]);

        $sudahAda = Probabilitas::where('lokasi', $validated['lokasi'])
            ->whereRaw('ABS(tikor_lat - ?) < 0.0000001', [(float) $lat])
            ->whereRaw('ABS(tikor_lng - ?) < 0.0000001', [(float) $lng])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors(['lokasi' => 'Kandidat dengan lokasi dan titik koordinat yang sama sudah pernah ditambahkan.']);
        }

        $probabilitas = Probabilitas::create([
            'lokasi'        => $validated['lokasi'],
            'alamat'        => $validated['alamat'] ?? null,
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'pic'           => $validated['pic'] ?? null,
            'tikor_lat'     => $lat,
            'tikor_lng'     => $lng,
            'created_by'    => $request->user()?->id,
        ]);

        $this->kandidatSync->sync($probabilitas);

        return redirect()
            ->route('monitoring.probabilitas.index')
            ->with('success', 'Kandidat baru berhasil ditambahkan.');
    }
    /**
     * Data JSON satu lokasi untuk mengisi modal edit / popup detail
     * kandidat — termasuk badge tahapan.
     */
    public function editData(Probabilitas $probabilitas)
    {
        $probabilitas->load('riwayatTahapan');

        return response()->json([
            'probabilitas' => $probabilitas,
            'badges' => $probabilitas->badgePerTahap(),
        ]);
    }

    /**
     * Update field identitas + poin (bagian atas modal edit).
     * Tidak menyentuh tahapan_probings — itu ditangani endpoint terpisah
     * (storeTahapan) supaya riwayat kunjungan tidak ikut ter-overwrite.
     */
    public function update(Request $request, Probabilitas $probabilitas)
    {
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:30',
            'pic' => 'nullable|string|max:255',
            'tikor_lat' => 'required|numeric|between:-90,90',
            'tikor_lng' => 'required|numeric|between:-180,180',
            'ulp' => 'required|string|max:255',
            'skema' => 'nullable|string|max:255',
            'kebutuhan_22kw' => 'nullable|integer|min:0',
            'kebutuhan_30kw' => 'nullable|integer|min:0',
            'kebutuhan_50kw' => 'nullable|integer|min:0',
            'kebutuhan_60kw' => 'nullable|integer|min:0',
            'kebutuhan_120kw' => 'nullable|integer|min:0',
            'kebutuhan_180kw' => 'nullable|integer|min:0',
            'mitra_mesin' => 'nullable|string|max:255',
            'poin_perluasan_jaringan' => 'nullable|numeric|min:0|max:2',
            'fasilitas_ruang_tunggu' => 'boolean',
            'fasilitas_parkir' => 'boolean',
            'fasilitas_toilet' => 'boolean',
            'fasilitas_kafe' => 'boolean',
            'okupansi_perumahan' => 'boolean',
            'okupansi_pintu_tol' => 'boolean',
            'okupansi_pusat_keramaian' => 'boolean',
            'okupansi_ruas_jalan' => 'boolean',
            'keterangan' => 'nullable|string',
        ]);

        $probabilitas->update($validated);

        $this->kandidatSync->sync($probabilitas);

        return redirect()
            ->route('monitoring.probabilitas.index')
            ->with('success', "Data \"{$probabilitas->lokasi}\" diperbarui.");
    }

    /**
     * Tambah satu entri kunjungan baru untuk sebuah tahap (dipanggil dari
     * modal riwayat). Setelah simpan, langsung recalculate skor terkait.
     */
    public function storeTahapan(Request $request, Probabilitas $probabilitas)
    {
        $validated = $request->validate([
            'tahap' => 'required|in:' . implode(',', array_keys(Probabilitas::TAHAPAN)),
            'tanggal' => 'required|date',
            'petugas_pic' => 'nullable|string|max:255',
            'hasil' => 'required|in:berhasil,perlu_kunjungan_ulang,gagal',
            'catatan' => 'nullable|string',
        ]);

        $riwayat = $probabilitas->riwayatTahapan()->create([
            ...$validated,
            'created_by' => $request->user()?->id,
        ]);

        $this->scoreService->recalculate($probabilitas);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kunjungan tercatat.',
                'riwayat' => $riwayat,
            ]);
        }

        return back()->with('success', 'Kunjungan tercatat.');
    }

    /**
     * Riwayat satu tahap untuk sebuah lokasi — versi JSON (dipanggil AJAX
     * saat badge di grid diklik, untuk isi modal ringkas).
     */
    public function riwayatTahap(Probabilitas $probabilitas, string $tahap)
    {
        abort_unless(array_key_exists($tahap, Probabilitas::TAHAPAN), 404);

        $riwayat = $probabilitas->riwayatTahapan()
            ->where('tahap', $tahap)
            ->orderByDesc('tanggal')
            ->with('pencatat')
            ->get();

        return response()->json([
            'tahap' => Probabilitas::TAHAPAN[$tahap],
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Riwayat satu tahap untuk sebuah lokasi — versi halaman penuh
     * (dibuka saat klik "Lihat Semua Riwayat" dari modal ringkas).
     */
    public function riwayatLengkap(Probabilitas $probabilitas, string $tahap)
    {
        abort_unless(array_key_exists($tahap, Probabilitas::TAHAPAN), 404);

        $riwayat = $probabilitas->riwayatTahapan()
            ->where('tahap', $tahap)
            ->orderByDesc('tanggal')
            ->with('pencatat')
            ->get();

        return view('monitoring.probabilitas.riwayat-lengkap', [
            'probabilitas' => $probabilitas,
            'tahap'        => $tahap,
            'tahapLabel'   => Probabilitas::TAHAPAN[$tahap],
            'riwayat'      => $riwayat,
        ]);
    }

    public function destroyTahapan(Probabilitas $probabilitas, TahapanProbing $tahapanProbing)
    {
        abort_unless($tahapanProbing->probabilitas_id === $probabilitas->id, 404);

        $tahapanProbing->delete();
        $this->scoreService->recalculate($probabilitas);

        return back()->with('success', 'Entri kunjungan dihapus.');
    }
}