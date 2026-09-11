<?php

namespace App\Http\Controllers;

use App\Models\FsSkema;
use App\Models\KandidatPrioritas;
use App\Services\FsSkemaCalculatorService;
use App\Services\NarasiGeneratorService;
use Illuminate\Http\Request;

class FsSkemaController extends Controller
{
    public function __construct(
        protected FsSkemaCalculatorService $calculator,
        protected NarasiGeneratorService $narasiGenerator,
    ) {}

    public function index(Request $request)
    {
        $fsSkemas = FsSkema::with('kandidat')
            ->when($request->skema, fn ($q) => $q->where('skema', $request->skema))
            ->latest()
            ->paginate(10);

        return view('fs-skema.index', compact('fsSkemas'));
    }

    public function create()
    {
        $kandidatList = KandidatPrioritas::all();

        return view('fs-skema.create', compact('kandidatList'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);
        $fsSkema = $this->simpanDenganPerhitungan($validated);

        return redirect()->route('fs-skema.show', $fsSkema)
            ->with('success', 'FS Skema berhasil dibuat.');
    }

    public function show(FsSkema $fsSkema)
    {
        $fsSkema->load('kandidat');

        // [DICABUT sejak keputusan "numpang baca dari modul Kandidat"] 3 SPKLU Terdekat
        // TIDAK dihitung sendiri lagi di sini. Haversine (hitungJarakKm/cari3SpkluTerdekat
        // di FsSkemaCalculatorService) masih ada di file itu, tapi ditandai TIDAK DIPAKAI —
        // disimpan cuma untuk referensi/fallback darurat kalau suatu saat dibutuhkan lagi.
        //
        // TODO (menunggu modul Kandidat milik tim lain selesai, pakai Google Distance
        // Matrix API — jarak rute kendaraan asli, bukan garis lurus): ganti baris di bawah
        // jadi ambil data asli, contoh:
        //   $spkluTerdekat = $fsSkema->kandidat->spkluTerdekat ?? [];
        // (sesuaikan nama relasi/kolom setelah struktur tabel kandidat_prioritas fix)
        $spkluTerdekat = [];

        $proyeksiRoi = $this->calculator->hitungProyeksiROI($fsSkema);

        return view('fs-skema.show', compact('fsSkema', 'spkluTerdekat', 'proyeksiRoi'));
    }

    public function edit(FsSkema $fsSkema)
    {
        $kandidatList = KandidatPrioritas::all();

        return view('fs-skema.edit', compact('fsSkema', 'kandidatList'));
    }

    public function update(Request $request, FsSkema $fsSkema)
    {
        $validated = $this->validasi($request);

        $poinFasilitas = $this->calculator->hitungPoinFasilitas($validated['fasilitas'] ?? []);
        $poinJaringan = $this->calculator->hitungPoinKesiapanJaringan($validated['kesiapan_jaringan'] ?? null);
        $poinOkupansi = $this->calculator->hitungPoinOkupansi($validated['okupansi'] ?? []);
        $totalPoin = $this->calculator->hitungTotalPoin($poinFasilitas, $poinJaringan, $poinOkupansi);
        $status = $this->calculator->tentukanStatusKelayakan($totalPoin);

        $fsSkema->update([
            ...$validated,
            'poin_fasilitas' => $poinFasilitas,
            'poin_kesiapan_jaringan' => $poinJaringan,
            'poin_okupansi' => $poinOkupansi,
            'total_poin' => $totalPoin,
            'status_kelayakan' => $status,
        ]);

        $fsSkema->update(['narasi_analisis' => $this->narasiGenerator->buatNarasi($fsSkema)]);

        return redirect()->route('fs-skema.show', $fsSkema)
            ->with('success', 'FS Skema berhasil diperbarui.');
    }

    public function destroy(FsSkema $fsSkema)
    {
        $fsSkema->delete();

        return redirect()->route('fs-skema.index')->with('success', 'FS Skema berhasil dihapus.');
    }

    protected function validasi(Request $request): array
    {
        return $request->validate([
            // [WAJIB] 3 SPKLU Terdekat ditarik dari data Kandidat, jadi FS Skema
            // WAJIB terhubung ke satu Kandidat — tidak boleh kosong lagi.
            'kandidat_id' => 'required|exists:kandidat_prioritas,id',

            'skema' => 'required|in:skema_2,skema_3',
            'nama_lokasi' => 'required|string|max:255',
            'titik_koordinat' => 'nullable|string',

            // Skema 2
            'total_rab_investasi' => 'required_if:skema,skema_2|nullable|numeric|min:0',

            // Skema 3
            'rab_mitra_mesin' => 'required_if:skema,skema_3|nullable|numeric|min:0',
            'rab_mitra_lahan' => 'required_if:skema,skema_3|nullable|numeric|min:0',
            'sharing_provit_mitra_lahan' => 'nullable|numeric|min:0|max:1',

            'layanan_listrik' => 'nullable|in:TM,TR,LTR',
            'mobil_per_hari' => 'required|integer|min:0',
            'transaksi_kwh_per_mobil' => 'required|numeric|min:0',
            'fasilitas' => 'nullable|array',
            'kesiapan_jaringan' => 'nullable|string',
            'okupansi' => 'nullable|array',
        ]);
    }

    protected function simpanDenganPerhitungan(array $validated): FsSkema
    {
        $poinFasilitas = $this->calculator->hitungPoinFasilitas($validated['fasilitas'] ?? []);
        $poinJaringan = $this->calculator->hitungPoinKesiapanJaringan($validated['kesiapan_jaringan'] ?? null);
        $poinOkupansi = $this->calculator->hitungPoinOkupansi($validated['okupansi'] ?? []);
        $totalPoin = $this->calculator->hitungTotalPoin($poinFasilitas, $poinJaringan, $poinOkupansi);
        $status = $this->calculator->tentukanStatusKelayakan($totalPoin);

        $fsSkema = FsSkema::create([
            ...$validated,
            'poin_fasilitas' => $poinFasilitas,
            'poin_kesiapan_jaringan' => $poinJaringan,
            'poin_okupansi' => $poinOkupansi,
            'total_poin' => $totalPoin,
            'status_kelayakan' => $status,
            'created_by' => auth()->id(),
        ]);

        $fsSkema->update(['narasi_analisis' => $this->narasiGenerator->buatNarasi($fsSkema)]);

        return $fsSkema;
    }
}