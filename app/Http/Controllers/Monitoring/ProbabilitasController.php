<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Probabilitas;
use App\Models\TahapanProbing;
use App\Services\ProbabilitasScoreService;
use Illuminate\Http\Request;

class ProbabilitasController extends Controller
{
    public function __construct(protected ProbabilitasScoreService $scoreService)
    {
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

        $daftarProbabilitas = Probabilitas::query()
            // filter search & kategori yang sudah ada...
            ->paginate(15);

        $daftarUlp = \App\Models\UlpMapping::orderBy('nama_penuh')->get();

        return view('monitoring.probabilitas.index', compact('daftarProbabilitas', 'daftarUlp'));
    }

    /**
     * Tambah lokasi baru — SENGAJA hanya minta field identitas dasar.
     * Field penilaian (fasilitas, okupansi, kebutuhan mesin, dst) tidak
     * wajib diisi di sini; diisi belakangan lewat update().
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lokasi'    => 'required|string|max:255',
            'tikor_lat' => 'required|numeric',
            'tikor_lng' => 'required|numeric',
            'ulp'       => 'required|string',
            'skema'     => 'nullable|string',
        ]);

        Probabilitas::create($validated);

        return redirect()
            ->route('monitoring.probabilitas.index')
            ->with('success', 'Lokasi kandidat berhasil ditambahkan.');
    }

    /**
     * Data JSON satu lokasi untuk mengisi modal edit (dipanggil AJAX saat
     * tombol pensil di grid diklik) — termasuk badge tahapan supaya modal
     * bisa render status "Selesai"/jumlah kunjungan tanpa request kedua.
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
     * Update field identitas + poin (bagian atas modal edit di Image 2).
     * Tidak menyentuh tahapan_probings — itu ditangani endpoint terpisah
     * (storeTahapan) supaya riwayat kunjungan tidak ikut ter-overwrite
     * setiap kali user simpan perubahan poin/fasilitas.
     */
    public function update(Request $request, Probabilitas $probabilitas)
    {
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
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
            'poin_perluasan_jaringan' => 'nullable|integer|min:0',
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

        return redirect()
            ->route('monitoring.probabilitas.index')
            ->with('success', "Data \"{$probabilitas->lokasi}\" diperbarui.");
    }

    /**
     * Tambah satu entri kunjungan baru untuk sebuah tahap (dipanggil dari
     * modal riwayat, bukan dari form update() utama). Setelah simpan,
     * langsung recalculate persentase & kategori lokasi terkait.
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
     * Riwayat lengkap satu tahap untuk sebuah lokasi (dipanggil AJAX saat
     * badge di grid diklik).
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

    public function destroyTahapan(Probabilitas $probabilitas, TahapanProbing $tahapanProbing)
    {
        abort_unless($tahapanProbing->probabilitas_id === $probabilitas->id, 404);

        $tahapanProbing->delete();
        $this->scoreService->recalculate($probabilitas);

        return back()->with('success', 'Entri kunjungan dihapus.');
    }
}