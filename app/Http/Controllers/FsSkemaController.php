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
        $kandidats = KandidatPrioritas::orderBy('nama_lokasi')->get();
        return view('fs-skema.create', compact('kandidats'));
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
        $fsSkema->load('kandidat', 'pengajuan');

        $proyeksiRoi = $this->calculator->hitungProyeksiROI(
            (float) $fsSkema->total_rab_investasi,
            $fsSkema->mobil_per_hari,
            (float) $fsSkema->transaksi_kwh_per_mobil,
        );

        return view('fs-skema.show', compact('fsSkema', 'proyeksiRoi'));
    }

    public function edit(FsSkema $fsSkema)
    {
        $kandidats = KandidatPrioritas::orderBy('nama_lokasi')->get();
        return view('fs-skema.edit', compact('fsSkema', 'kandidats'));
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
        if ($fsSkema->pengajuan()->exists()) {
            return back()->with('error', 'FS Skema tidak bisa dihapus karena sudah punya pengajuan terkait.');
        }

        $fsSkema->delete();

        return redirect()->route('fs-skema.index')->with('success', 'FS Skema berhasil dihapus.');
    }

    protected function validasi(Request $request): array
    {
        return $request->validate([
            'kandidat_id' => 'nullable|exists:kandidat_prioritas,id',
            'skema' => 'required|in:skema_2,skema_3',
            'nama_lokasi' => 'required|string|max:255',
            'titik_koordinat' => 'nullable|string',
            'total_rab_investasi' => 'required|numeric|min:0',
            'mobil_per_hari' => 'required|integer|min:0',
            'layanan_listrik' => 'nullable|string',
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