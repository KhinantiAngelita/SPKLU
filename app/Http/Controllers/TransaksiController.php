<?php

namespace App\Http\Controllers;

use App\Helpers\NotifikasiHelper;
use App\Jobs\ProsesImportTransaksiJob;
use App\Models\Spklu;
use App\Models\SpkluAlias;
use App\Models\Transaksi;
use App\Models\TransaksiUnmatchedName;
use App\Models\TransaksiUpload;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    protected const DATA_HISTORIS_KWH = [
        2024 => [
            '01' => 6333, '02' => 9542, '03' => 9091, '04' => 11806,
            '05' => 13019, '06' => 13605, '07' => 19166, '08' => 22393,
            '09' => 29073, '10' => 40290, '11' => 40647, '12' => 45362,
        ],
        2025 => [
            '01' => 47599.96, '02' => 47792.15, '03' => 67349.23, '04' => 95173.60,
            '05' => 118398.55, '06' => 132797.12, '07' => 153255.16, '08' => 140356.97,
            '09' => 179959.67, '10' => 198571.53, '11' => 216287.45, '12' => 253080.26,
        ],
    ];

    protected const JUMLAH_BULAN_MATRIKS = 13;

    public function index(Request $request)
    {
        $spkluId = $request->spklu_id;
        $satuan = $request->satuan ?? 'kali';

        $kolom = match ($satuan) {
            'kwh' => 'energi_kwh',
            'rp' => 'pendapatan_rp',
            default => 'jumlah_transaksi',
        };

        $minTanggal = Transaksi::min('tanggal');
        $maxTanggal = Transaksi::max('tanggal');
        $mulai = $request->dari ? Carbon::parse($request->dari) : ($minTanggal ? Carbon::parse($minTanggal) : now()->startOfYear());
        $sampai = $request->sampai ? Carbon::parse($request->sampai) : ($maxTanggal ? Carbon::parse($maxTanggal) : now());

        $ringkasan = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulai->format('Y-m-d'), $sampai->format('Y-m-d')])
            ->selectRaw('SUM(jumlah_transaksi) as total_transaksi, SUM(energi_kwh) as total_energi, SUM(pendapatan_rp) as total_pendapatan')
            ->first();

        $totalTransaksi = (int) ($ringkasan->total_transaksi ?? 0);
        $totalEnergi = (float) ($ringkasan->total_energi ?? 0);
        $totalPendapatan = (float) ($ringkasan->total_pendapatan ?? 0);
        $rataRataKwhPerTransaksi = $totalTransaksi > 0 ? $totalEnergi / $totalTransaksi : 0;

        $durasiHari = $mulai->diffInDays($sampai) + 1;
        $mulaiSebelumnya = $mulai->copy()->subDays($durasiHari);
        $sampaiSebelumnya = $mulai->copy()->subDay();

        $ringkasanSebelumnya = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulaiSebelumnya, $sampaiSebelumnya])
            ->selectRaw('SUM(jumlah_transaksi) as total_transaksi, SUM(energi_kwh) as total_energi, SUM(pendapatan_rp) as total_pendapatan')
            ->first();

        $trendPersen = function ($sekarang, $dulu) {
            if (! $dulu || $dulu == 0) {
                return $sekarang > 0 ? 100 : 0;
            }

            return round((($sekarang - $dulu) / $dulu) * 100, 1);
        };

        $trend = [
            'transaksi' => $trendPersen($totalTransaksi, (int) ($ringkasanSebelumnya->total_transaksi ?? 0)),
            'energi' => $trendPersen($totalEnergi, (float) ($ringkasanSebelumnya->total_energi ?? 0)),
            'pendapatan' => $trendPersen($totalPendapatan, (float) ($ringkasanSebelumnya->total_pendapatan ?? 0)),
        ];

        $rincian = Transaksi::query()
            ->with('spklu')
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->orderByDesc('tanggal')
            ->paginate(15)
            ->withQueryString();

        $tahunTersedia = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->selectRaw('DISTINCT YEAR(tanggal) as tahun')
            ->orderBy('tahun')
            ->pluck('tahun')
            ->map(fn ($t) => (int) $t)
            ->values();

        if ($kolom === 'energi_kwh' && ! $spkluId) {
            $tahunTersedia = $tahunTersedia
                ->concat(array_keys(self::DATA_HISTORIS_KWH))
                ->unique()
                ->sort()
                ->values();
        }

        $tahunDipilih = $request->has('tahun')
            ? array_map('intval', (array) $request->input('tahun'))
            : $tahunTersedia->toArray();
        sort($tahunDipilih);

        $bulanAwal = max(1, min(12, (int) ($request->bulan_awal ?? 1)));
        $bulanAkhir = max(1, min(12, (int) ($request->bulan_akhir ?? 12)));
        if ($bulanAwal > $bulanAkhir) {
            [$bulanAwal, $bulanAkhir] = [$bulanAkhir, $bulanAwal];
        }

        $trenPerTahun = $this->buildTrenPerTahunBulanan($tahunDipilih, $spkluId, $kolom, $bulanAwal, $bulanAkhir);

        $jumlahBarisPerTahun = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->selectRaw('YEAR(tanggal) as tahun, COUNT(*) as jumlah')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $matriksData = $this->buildMatriksBulananPerSpklu();

        return view('transaksi.index', [
            'spkluList' => Spklu::aktif()->orderBy('nama')->get(),
            'spkluTerpilih' => $spkluId,
            'satuan' => $satuan,
            'dari' => $mulai->format('Y-m-d'),
            'sampai' => $sampai->format('Y-m-d'),

            'totalTransaksi' => $totalTransaksi,
            'totalEnergi' => $totalEnergi,
            'totalPendapatan' => $totalPendapatan,
            'rataRataKwhPerTransaksi' => $rataRataKwhPerTransaksi,
            'trend' => $trend,
            'rincian' => $rincian,

            'tahunTersedia' => $tahunTersedia,
            'tahunDipilih' => $tahunDipilih,
            'bulanAwal' => $bulanAwal,
            'bulanAkhir' => $bulanAkhir,
            'trenPerTahun' => $trenPerTahun,
            'jumlahBarisPerTahun' => $jumlahBarisPerTahun,

            'periodeMatriks' => $matriksData['periodeList'],
            'matriksKaliTransaksi' => $matriksData['matriks'],
        ]);
    }

    private function buildTrenPerTahunBulanan(array $tahunList, $spkluId, string $kolom, int $bulanAwal, int $bulanAkhir): array
    {
        $hasil = [];

        foreach ($tahunList as $tahun) {
            if ($kolom === 'energi_kwh' && ! $spkluId && isset(self::DATA_HISTORIS_KWH[$tahun])) {
                $bulanan = self::DATA_HISTORIS_KWH[$tahun];

                $hasil[$tahun] = collect(range($bulanAwal, $bulanAkhir))
                    ->map(fn ($b) => (float) ($bulanan[str_pad($b, 2, '0', STR_PAD_LEFT)] ?? 0))
                    ->values()
                    ->toArray();

                continue;
            }

            $perBulan = Transaksi::query()
                ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
                ->whereYear('tanggal', $tahun)
                ->whereRaw('MONTH(tanggal) BETWEEN ? AND ?', [$bulanAwal, $bulanAkhir])
                ->selectRaw("MONTH(tanggal) as bulan, SUM({$kolom}) as total")
                ->groupBy('bulan')
                ->pluck('total', 'bulan');

            $hasil[$tahun] = collect(range($bulanAwal, $bulanAkhir))
                ->map(fn ($b) => round((float) ($perBulan[$b] ?? 0), 2))
                ->values()
                ->toArray();
        }

        return $hasil;
    }

    private function buildMatriksBulananPerSpklu(): array
    {
        $bulanAkhir = now()->startOfMonth();
        $bulanAwal = $bulanAkhir->copy()->subMonths(self::JUMLAH_BULAN_MATRIKS - 1);

        $periodeList = collect(range(0, self::JUMLAH_BULAN_MATRIKS - 1))
            ->map(fn ($i) => $bulanAwal->copy()->addMonths($i)->format('Y-m'));

        $dataMentah = Transaksi::query()
            ->join('spklus', 'spklus.id', '=', 'transaksis.spklu_id')
            ->whereBetween('transaksis.tanggal', [$bulanAwal, $bulanAkhir->copy()->endOfMonth()])
            ->selectRaw("
                spklus.id as spklu_id,
                DATE_FORMAT(transaksis.tanggal, '%Y-%m') as bulan,
                SUM(transaksis.jumlah_transaksi) as total
            ")
            ->groupBy('spklus.id', 'bulan')
            ->get()
            ->groupBy('spklu_id');

        $spkluAktif = Spklu::aktif()
            ->orderBy('kode_unit')
            ->orderBy('nama')
            ->get(['id', 'nama', 'kode_unit']);

        $matriks = $spkluAktif
            ->map(function ($spklu) use ($dataMentah, $periodeList) {
                $barisBulan = $dataMentah->get($spklu->id, collect())->pluck('total', 'bulan');

                $perBulan = $periodeList->map(
                    fn ($p) => isset($barisBulan[$p]) ? (int) $barisBulan[$p] : null
                );

                $terisi = $perBulan->filter(fn ($v) => $v !== null);

                return [
                    'kode_unit' => $spklu->kode_unit,
                    'nama' => $spklu->nama,
                    'per_bulan' => $perBulan,
                    'rata_rata' => $terisi->isNotEmpty() ? round($terisi->avg()) : null,
                ];
            })
            ->filter(fn ($row) => $row['per_bulan']->filter(fn ($v) => $v !== null)->isNotEmpty())
            ->values();

        return [
            'periodeList' => $periodeList,
            'matriks' => $matriks,
        ];
    }

    public function uploadPage()
    {
        $namaSudahDialias = SpkluAlias::pluck('nama_asli')->all();

        $riwayat = TransaksiUpload::with(['diuploadOleh', 'unmatchedNames'])->latest()->paginate(15);

        $riwayat->getCollection()->transform(function ($upload) use ($namaSudahDialias) {
            $upload->unresolvedUnmatched = $upload->unmatchedNames
                ->filter(fn ($u) => ! in_array($u->nama_asli, $namaSudahDialias))
                ->values();

            return $upload;
        });

        return view('transaksi.upload', [
            'riwayat' => $riwayat,
            'aliasList' => SpkluAlias::with('spklu')->latest()->get(),
            'spkluList' => Spklu::aktif()->orderBy('nama')->get(),

            'unmatchedList' => TransaksiUnmatchedName::whereNotIn('nama_asli', $namaSudahDialias)
                ->orderByDesc('jumlah_baris_total')
                ->get(),
        ]);
    }

    public function export(Request $request)
    {
        $spkluId = $request->spklu_id;
        $spkluTerpilih = $spkluId ? Spklu::find($spkluId) : null;

        $minTanggal = Transaksi::min('tanggal');
        $maxTanggal = Transaksi::max('tanggal');
        $mulai = $request->dari ? Carbon::parse($request->dari) : ($minTanggal ? Carbon::parse($minTanggal) : now()->startOfYear());
        $sampai = $request->sampai ? Carbon::parse($request->sampai) : ($maxTanggal ? Carbon::parse($maxTanggal) : now());

        $baseQuery = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulai->format('Y-m-d'), $sampai->format('Y-m-d')]);

        $kpi = (clone $baseQuery)
            ->selectRaw('
                COUNT(id) as total_baris,
                SUM(jumlah_transaksi) as total_transaksi,
                SUM(energi_kwh) as total_energi,
                SUM(pendapatan_rp) as total_pendapatan,
                SUM(total_durasi_menit) as total_durasi,
                COUNT(DISTINCT spklu_id) as total_spklu_aktif
            ')
            ->first();

        $totalTransaksi = (int) ($kpi->total_transaksi ?? 0);
        $totalEnergi = (float) ($kpi->total_energi ?? 0);
        $totalPendapatan = (float) ($kpi->total_pendapatan ?? 0);
        $totalDurasi = (int) ($kpi->total_durasi ?? 0);
        $totalSpkluAktif = (int) ($kpi->total_spklu_aktif ?? 0);

        $rataKwh = $totalTransaksi > 0 ? $totalEnergi / $totalTransaksi : 0;
        $rataRp = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;
        $rataDurasi = $totalTransaksi > 0 ? round($totalDurasi / $totalTransaksi) : 0;

        $rekapSpklu = Transaksi::query()
            ->join('spklus', 'spklus.id', '=', 'transaksis.spklu_id')
            ->leftJoin('ulp_mappings', 'ulp_mappings.id', '=', 'spklus.ulp_mapping_id')
            ->when($spkluId, fn ($q) => $q->where('transaksis.spklu_id', $spkluId))
            ->whereBetween('transaksis.tanggal', [$mulai->format('Y-m-d'), $sampai->format('Y-m-d')])
            ->selectRaw('
                spklus.id as spklu_id,
                spklus.nama as nama_spklu,
                spklus.kode_unit,
                ulp_mappings.nama_singkat as nama_ulp,
                SUM(transaksis.jumlah_transaksi) as total_transaksi,
                SUM(transaksis.energi_kwh) as total_energi,
                SUM(transaksis.pendapatan_rp) as total_pendapatan,
                SUM(transaksis.total_durasi_menit) as total_durasi
            ')
            ->groupBy('spklus.id', 'spklus.nama', 'spklus.kode_unit', 'ulp_mappings.nama_singkat')
            ->orderByDesc('total_transaksi')
            ->get();

        $rekapBulanan = (clone $baseQuery)
            ->selectRaw("
                DATE_FORMAT(tanggal, '%Y-%m') as bulan,
                SUM(jumlah_transaksi) as total_transaksi,
                SUM(energi_kwh) as total_energi,
                SUM(pendapatan_rp) as total_pendapatan
            ")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $isSpkluKhusus = ! empty($spkluId);
        $rincianQuery = (clone $baseQuery)->with('spklu');

        if ($isSpkluKhusus) {
            $rincian = $rincianQuery->orderByDesc('tanggal')->get();
        } else {
            $rincian = $rincianQuery->orderByDesc('jumlah_transaksi')->limit(50)->get();
        }

        $pdf = Pdf::loadView('transaksi.export-pdf', compact(
            'spkluTerpilih',
            'mulai',
            'sampai',
            'totalTransaksi',
            'totalEnergi',
            'totalPendapatan',
            'totalDurasi',
            'totalSpkluAktif',
            'rataKwh',
            'rataRp',
            'rataDurasi',
            'rekapSpklu',
            'rekapBulanan',
            'rincian',
            'isSpkluKhusus'
        ))->setPaper('a4', 'portrait');

        $slugSpklu = $spkluTerpilih ? Str::slug($spkluTerpilih->nama) : 'semua-spklu';
        $namaFile = 'Laporan-Transaksi-SPKLU-'.$slugSpklu.'-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($namaFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx,xls,csv|mimes:xlsx,xls,csv,txt|max:51200',
        ]);

        $uploadedFile = $request->file('file');
        $namaFileAsli = $uploadedFile->getClientOriginalName();
        $ukuranBytes = $uploadedFile->getSize();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        $uploadLog = TransaksiUpload::create([
            'nama_file' => $namaFileAsli,
            'ukuran_bytes' => $ukuranBytes,
            'status' => 'diproses',
            'diupload_oleh' => $request->user()->id,
        ]);

        if (! $this->simpanFileMentah($uploadedFile, $uploadLog)) {
            $uploadLog->update([
                'status' => 'gagal',
                'pesan_error' => 'Gagal menyimpan file ke storage server. Cek permission folder storage/app/private.',
            ]);

            $pesan = 'Gagal menyimpan file ke server. Silakan coba lagi atau hubungi admin.';

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'nama_file' => $namaFileAsli, 'message' => $pesan], 422);
            }

            return back()->with('error', $pesan);
        }

        Log::info('=== TRANSAKSI IMPORT (dispatch ke queue) ===', ['file' => $namaFileAsli, 'upload_id' => $uploadLog->id]);

        ProsesImportTransaksiJob::dispatch($uploadLog->id, $extension, $request->user()->id);

        NotifikasiHelper::kirim(
            'transaksi',
            "File transaksi \"{$namaFileAsli}\" diunggah dan sedang diproses sistem.",
            'arrow-left-right',
            route('transaksi.upload'),
            null,
            'Upload Transaksi Baru'
        );

        $pesan = "File \"{$namaFileAsli}\" sedang diproses di background. Refresh halaman ini beberapa saat lagi untuk melihat hasilnya.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'nama_file' => $namaFileAsli,
                'status' => 'diproses',
                'message' => $pesan,
            ]);
        }

        return back()->with('success', $pesan);
    }

    public function reupload(Request $request, TransaksiUpload $transaksiUpload)
    {
        $request->validate([
            'file' => 'required|file|extensions:xlsx,xls,csv|mimes:xlsx,xls,csv,txt|max:51200',
        ]);

        $uploadedFile = $request->file('file');
        $namaFileAsli = $uploadedFile->getClientOriginalName();
        $ukuranBytes = $uploadedFile->getSize();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        Log::info('=== TRANSAKSI REUPLOAD (ganti file) START ===', [
            'upload_id' => $transaksiUpload->id,
            'file' => $namaFileAsli,
        ]);

        DB::transaction(function () use ($transaksiUpload) {
            $this->kurangiAkumulasiUnmatchedGlobal($transaksiUpload);

            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
        });

        if ($transaksiUpload->path_file) {
            Storage::delete($transaksiUpload->path_file);
        }

        $transaksiUpload->update([
            'nama_file' => $namaFileAsli,
            'ukuran_bytes' => $ukuranBytes,
            'status' => 'diproses',
            'pesan_error' => null,
            'path_file' => null,
        ]);

        if (! $this->simpanFileMentah($uploadedFile, $transaksiUpload)) {
            $transaksiUpload->update([
                'status' => 'gagal',
                'pesan_error' => 'Gagal menyimpan file ke storage server.',
            ]);

            return back()->with('error', 'Gagal menyimpan file ke server. Silakan coba lagi.');
        }

        ProsesImportTransaksiJob::dispatch($transaksiUpload->id, $extension, $request->user()->id);

        NotifikasiHelper::kirim(
            'transaksi',
            "File transaksi baru \"{$namaFileAsli}\" diunggah ulang dan sedang diproses sistem.",
            'arrow-left-right',
            route('transaksi.upload'),
            null,
            'Unggah Ulang Transaksi'
        );

        return back()->with('success', "File \"{$namaFileAsli}\" sedang diproses ulang di background. Refresh halaman ini beberapa saat lagi.");
    }

    public function reprocess(Request $request, TransaksiUpload $transaksiUpload)
    {
        if (! $transaksiUpload->path_file || ! Storage::exists($transaksiUpload->path_file)) {
            $pesan = 'File asli untuk riwayat ini sudah tidak tersimpan di server. Silakan pakai "Ganti File" untuk upload manual.';

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }

            return back()->with('error', $pesan);
        }

        Log::info('=== TRANSAKSI PROSES ULANG (dispatch ke queue) ===', [
            'upload_id' => $transaksiUpload->id,
            'path_file' => $transaksiUpload->path_file,
        ]);

        DB::transaction(function () use ($transaksiUpload) {
            $this->kurangiAkumulasiUnmatchedGlobal($transaksiUpload);

            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
        });

        $transaksiUpload->update([
            'status' => 'diproses',
            'pesan_error' => null,
        ]);

        $extension = strtolower(pathinfo($transaksiUpload->path_file, PATHINFO_EXTENSION));

        ProsesImportTransaksiJob::dispatch($transaksiUpload->id, $extension, $request->user()->id);

        return back()->with('success', 'File sedang diproses ulang di background. Refresh halaman ini beberapa saat lagi.');
    }

    private function simpanFileMentah($uploadedFile, TransaksiUpload $uploadLog): bool
    {
        try {
            $extensi = strtolower($uploadedFile->getClientOriginalExtension());
            $namaTersimpan = $uploadLog->id.'_'.now()->format('YmdHis').'.'.$extensi;
            $path = $uploadedFile->storeAs('transaksi-uploads', $namaTersimpan);

            if (! $path) {
                Log::error('simpanFileMentah: storeAs() balikin false/null', ['upload_id' => $uploadLog->id]);

                return false;
            }

            $uploadLog->path_file = $path;
            $uploadLog->save();

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan salinan file mentah', [
                'upload_id' => $uploadLog->id,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function kurangiAkumulasiUnmatchedGlobal(TransaksiUpload $transaksiUpload): void
    {
        $unmatchedDariUploadIni = $transaksiUpload->unmatchedNames()->get(['nama_asli', 'jumlah_baris']);

        foreach ($unmatchedDariUploadIni as $u) {
            $global = TransaksiUnmatchedName::where('nama_asli', $u->nama_asli)->first();

            if (! $global) {
                continue;
            }

            $sisaBaris = $global->jumlah_baris_total - $u->jumlah_baris;

            if ($sisaBaris <= 0) {
                $global->delete();
            } else {
                $global->update(['jumlah_baris_total' => $sisaBaris]);
            }
        }
    }

    public function uploadStatus(TransaksiUpload $transaksiUpload)
    {
        return response()->json([
            'id' => $transaksiUpload->id,
            'status' => $transaksiUpload->status,
            'pesan_error' => $transaksiUpload->pesan_error,
            'total_baris_diproses' => $transaksiUpload->total_baris_diproses,
            'total_rekap_tersimpan' => $transaksiUpload->total_rekap_tersimpan,
            'jumlah_nama_tidak_cocok' => $transaksiUpload->jumlah_nama_tidak_cocok,
            'selesai' => in_array($transaksiUpload->status, ['berhasil', 'gagal']),
        ]);
    }

    public function destroyUpload(TransaksiUpload $transaksiUpload)
    {
        $namaFile = $transaksiUpload->nama_file;
        $pathFile = $transaksiUpload->path_file;

        DB::transaction(function () use ($transaksiUpload) {
            $this->kurangiAkumulasiUnmatchedGlobal($transaksiUpload);

            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
            $transaksiUpload->delete();
        });

        if ($pathFile) {
            Storage::delete($pathFile);
        }

        return back()->with('success', "Riwayat \"{$namaFile}\" dan seluruh data transaksi yang tersimpan dari file itu berhasil dihapus.");
    }
}
