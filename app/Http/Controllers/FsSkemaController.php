<?php

namespace App\Http\Controllers;

use App\Helpers\NotifikasiHelper;
use App\Models\FsSkema;
use App\Models\Probabilitas;
use App\Services\FsSkemaCalculatorService;
use App\Services\NarasiGeneratorService;
use App\Services\SpkluTerdekatService;
use Illuminate\Http\Request;

class FsSkemaController extends Controller
{
    public function __construct(
        protected FsSkemaCalculatorService $calculator,
        protected NarasiGeneratorService $narasiGenerator,
        protected SpkluTerdekatService $spkluTerdekatService,
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
        $probabilitasList = $this->ambilProbabilitasUntukDropdown();
        $poinJaringanMap = $this->calculator->poinJaringanMapUntukJs();
        $poinJaringanFallback = $this->calculator->poinJaringanFallbackUntukJs();

        return view('fs-skema.create', compact('probabilitasList', 'poinJaringanMap', 'poinJaringanFallback'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);
        $fsSkema = $this->simpanDenganPerhitungan($validated, $request);

        NotifikasiHelper::kirim(
            'fs_skema',
            "Simulasi FS Skema untuk lokasi \"{$fsSkema->nama_lokasi}\" berhasil dibuat ({$fsSkema->status_kelayakan}).",
            'calculator',
            route('fs-skema.show', $fsSkema),
            ['super_admin', 'pemasaran', 'pengelola'],
            'Simulasi FS Skema Baru'
        );

        return redirect()->route('fs-skema.show', $fsSkema)
            ->with('success', 'FS Skema berhasil dibuat.');
    }

    public function show(FsSkema $fsSkema)
    {
        $fsSkema->load('kandidat');

        $spkluTerdekat = $this->hitungSpkluTerdekatDariKoordinat($fsSkema->titik_koordinat);
        $proyeksiRoi = $this->calculator->hitungProyeksiROI($fsSkema);
        $riwayatAnalisis = $fsSkema->riwayat()->latest()->get();

        return view('fs-skema.show', compact('fsSkema', 'spkluTerdekat', 'proyeksiRoi', 'riwayatAnalisis'));
    }

    public function edit(FsSkema $fsSkema)
    {
        $fsSkema->load('kandidat');

        $probabilitasList = $this->ambilProbabilitasUntukDropdown();
        $poinJaringanMap = $this->calculator->poinJaringanMapUntukJs();
        $poinJaringanFallback = $this->calculator->poinJaringanFallbackUntukJs();

        $spkluTerdekat = $this->hitungSpkluTerdekatDariKoordinat($fsSkema->titik_koordinat);
        $proyeksiRoi = $this->calculator->hitungProyeksiROI($fsSkema);

        return view('fs-skema.edit', compact(
            'fsSkema',
            'probabilitasList',
            'poinJaringanMap',
            'poinJaringanFallback',
            'spkluTerdekat',
            'proyeksiRoi'
        ));
    }

    public function update(Request $request, FsSkema $fsSkema)
    {
        $validated = $this->validasi($request);
        $poin = $this->hitungSemuaPoin($validated);

        $fsSkema->update([
            ...$validated,
            'poin_fasilitas' => $poin['fasilitas'],
            'poin_kesiapan_jaringan' => $poin['jaringan'],
            'poin_okupansi' => $poin['okupansi'],
            'total_poin' => $poin['total'],
            'status_kelayakan' => $poin['status'],
        ]);

        $proyeksiRoi = $this->calculator->hitungProyeksiROI($fsSkema);
        $narasi = $this->narasiGenerator->buatNarasi($fsSkema, $proyeksiRoi);
        $fsSkema->update(['narasi_analisis' => $narasi]);

        $this->catatRiwayat($fsSkema, $poin, $narasi, $request);

        NotifikasiHelper::kirim(
            'fs_skema',
            "Parameter simulasi FS Skema \"{$fsSkema->nama_lokasi}\" telah diperbarui.",
            'calculator',
            route('fs-skema.show', $fsSkema),
            ['super_admin', 'pemasaran', 'pengelola'],
            'Pembaruan FS Skema'
        );

        return redirect()->route('fs-skema.show', $fsSkema)
            ->with('success', 'FS Skema berhasil diperbarui.');
    }

    public function destroy(FsSkema $fsSkema)
    {
        $fsSkema->delete();

        return redirect()->route('fs-skema.index')->with('success', 'FS Skema berhasil dihapus.');
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            'nama_lokasi' => 'nullable|string',
            'skema' => 'nullable|in:skema_2,skema_3',
            'titik_koordinat' => 'nullable|string',
            'layanan_listrik' => 'nullable|in:TM,TR,LTR',
            'total_rab_investasi' => 'nullable|numeric|min:0',
            'rab_mitra_mesin' => 'nullable|numeric|min:0',
            'rab_mitra_lahan' => 'nullable|numeric|min:0',
            'sharing_provit_mitra_lahan' => 'nullable|numeric|min:0|max:1',
            'mobil_per_hari' => 'nullable|integer|min:0',
            'transaksi_kwh_per_mobil' => 'nullable|numeric|min:0',
            'masa_kontrak_tahun' => 'nullable|integer|min:1|max:30',
            'fasilitas' => 'nullable|array',
            'kesiapan_jaringan' => 'nullable|string',
            'okupansi' => 'nullable|array',
        ]);

        $poin = $this->hitungSemuaPoin($data);

        $fsSkemaSementara = new FsSkema([
            ...$data,
            'poin_fasilitas' => $poin['fasilitas'],
            'poin_kesiapan_jaringan' => $poin['jaringan'],
            'poin_okupansi' => $poin['okupansi'],
            'total_poin' => $poin['total'],
            'status_kelayakan' => $poin['status'],
        ]);

        $proyeksiRoi = ($data['mobil_per_hari'] ?? null) !== null && ($data['transaksi_kwh_per_mobil'] ?? null) !== null
            ? $this->calculator->hitungProyeksiROI($fsSkemaSementara)
            : null;

        $spkluTerdekat = $this->hitungSpkluTerdekatDariKoordinat($data['titik_koordinat'] ?? null);

        $narasi = null;
        if ($proyeksiRoi !== null) {
            try {
                $narasi = $this->narasiGenerator->buatNarasi($fsSkemaSementara, $proyeksiRoi);
            } catch (\Throwable $e) {
                $narasi = null;
            }
        }

        return response()->json([
            'poin' => $poin,
            'spklu_terdekat' => $spkluTerdekat,
            'proyeksi_roi' => $proyeksiRoi,
            'narasi_analisis' => $narasi,
        ]);
    }

    protected function validasi(Request $request): array
    {
        return $request->validate([
            'kandidat_id' => 'nullable|exists:kandidat_prioritas,id',
            'skema' => 'required|in:skema_2,skema_3',
            'nama_lokasi' => 'required|string|max:255',
            'titik_koordinat' => 'nullable|string',
            'total_rab_investasi' => 'required_if:skema,skema_2|nullable|numeric|min:0',
            'rab_mitra_mesin' => 'required_if:skema,skema_3|nullable|numeric|min:0',
            'rab_mitra_lahan' => 'required_if:skema,skema_3|nullable|numeric|min:0',
            'sharing_provit_mitra_lahan' => 'nullable|numeric|min:0|max:1',
            'layanan_listrik' => 'nullable|in:TM,TR,LTR',
            'mobil_per_hari' => 'required|integer|min:0',
            'transaksi_kwh_per_mobil' => 'required|numeric|min:0',
            'masa_kontrak_tahun' => 'required|integer|min:1|max:30',
            'fasilitas' => 'nullable|array',
            'kesiapan_jaringan' => 'nullable|string',
            'okupansi' => 'nullable|array',
        ]);
    }

    protected function simpanDenganPerhitungan(array $validated, Request $request): FsSkema
    {
        $poin = $this->hitungSemuaPoin($validated);

        $fsSkema = FsSkema::create([
            ...$validated,
            'poin_fasilitas' => $poin['fasilitas'],
            'poin_kesiapan_jaringan' => $poin['jaringan'],
            'poin_okupansi' => $poin['okupansi'],
            'total_poin' => $poin['total'],
            'status_kelayakan' => $poin['status'],
            'created_by' => auth()->id(),
        ]);

        $proyeksiRoi = $this->calculator->hitungProyeksiROI($fsSkema);
        $narasi = $this->narasiGenerator->buatNarasi($fsSkema, $proyeksiRoi);
        $fsSkema->update(['narasi_analisis' => $narasi]);

        $this->catatRiwayat($fsSkema, $poin, $narasi, $request);

        return $fsSkema;
    }

    protected function catatRiwayat(FsSkema $fsSkema, array $poin, ?string $narasi, Request $request): void
    {
        $fsSkema->riwayat()->create([
            'poin_fasilitas' => $poin['fasilitas'],
            'poin_kesiapan_jaringan' => $poin['jaringan'],
            'poin_okupansi' => $poin['okupansi'],
            'total_poin' => $poin['total'],
            'status_kelayakan' => $poin['status'],
            'narasi_analisis' => $narasi,
            'dicatat_oleh' => $request->user()?->id,
        ]);
    }

    protected function hitungSemuaPoin(array $data): array
    {
        $fasilitas = $this->calculator->hitungPoinFasilitas($data['fasilitas'] ?? []);
        $jaringan = $this->calculator->hitungPoinKesiapanJaringan($data['kesiapan_jaringan'] ?? null);
        $okupansi = $this->calculator->hitungPoinOkupansi($data['okupansi'] ?? []);
        $total = $this->calculator->hitungTotalPoin($fasilitas, $jaringan, $okupansi);
        $status = $this->calculator->tentukanStatusKelayakan($total, $fasilitas, $jaringan, $okupansi);

        return compact('fasilitas', 'jaringan', 'okupansi', 'total', 'status');
    }

    protected function hitungSpkluTerdekatDariKoordinat(?string $titikKoordinat): array
    {
        if (! $titikKoordinat) {
            return [];
        }

        $bagian = array_map('trim', explode(',', $titikKoordinat));

        if (count($bagian) !== 2 || ! is_numeric($bagian[0]) || ! is_numeric($bagian[1])) {
            return [];
        }

        return $this->spkluTerdekatService->cariTerdekat((float) $bagian[0], (float) $bagian[1], 3);
    }

    /**
     * Daftar lokasi dari Probabilitas untuk dropdown pencarian "Nama
     * Tempat/Lokasi" di form FS Skema. Eager-load kandidatPrioritas
     * (hasOne) supaya pas satu lokasi dipilih di combobox, kandidat_id
     * yang sudah tersambung bisa langsung auto-terisi (hidden field,
     * tidak lagi ada dropdown kandidat terpisah untuk diisi manual).
     */
    protected function ambilProbabilitasUntukDropdown()
    {
        return Probabilitas::with('kandidatPrioritas:id,probabilitas_id')
            ->orderBy('lokasi')
            ->get(['id', 'lokasi', 'tikor_lat', 'tikor_lng']);
    }
}
