<?php

namespace App\Http\Controllers;

use App\Imports\TransaksiImport;
use App\Models\Spklu;
use App\Models\SpkluAlias;
use App\Models\Transaksi;
use App\Models\TransaksiUnmatchedName;
use App\Models\TransaksiUpload;
use App\Models\TransaksiUploadUnmatched;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;

class TransaksiController extends Controller
{
    /**
     * Data historis 2024 & 2025 — FAKTA PERMANEN, bukan parameter yang bisa
     * diubah admin, jadi sengaja di-hardcode di sini (bukan di tabel
     * transaksis) buat menghindari masalah skema Spklu (id_spklu custom PK
     * yang gak auto-increment) kalau dipaksa lewat SPKLU dummy di database.
     * Cuma dipakai kalau satuan aktif = kWh; kalau satuan lain (Kali/Rp),
     * 2 tahun ini gak ditampilkan karena datanya emang cuma ada buat kWh.
     * Key bulan 2 digit ('01'..'12'), biar sejajar sama format DB asli.
     */
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

    public function index(Request $request)
    {
        $spkluId = $request->spklu_id;
        $satuan = $request->satuan ?? 'kali';

        $kolom = match ($satuan) {
            'kwh' => 'energi_kwh',
            'rp' => 'pendapatan_rp',
            default => 'jumlah_transaksi',
        };

        $mulai = $request->dari ? Carbon::parse($request->dari) : now()->subMonths(9);
        $sampai = $request->sampai ? Carbon::parse($request->sampai) : now();

        $ringkasan = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulai, $sampai])
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

        $aliasList = SpkluAlias::with('spklu')->orderBy('nama_asli')->get();

        $tahunTersedia = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->selectRaw('DISTINCT YEAR(tanggal) as tahun')
            ->orderBy('tahun')
            ->pluck('tahun')
            ->map(fn ($t) => (int) $t)
            ->values();

        // Tahun historis (2024/2025) cuma relevan buat satuan kWh, dan cuma
        // kalau TIDAK sedang difilter ke SPKLU tertentu (datanya agregat
        // seluruh SPKLU, bukan punya satu SPKLU spesifik).
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
            'aliasList' => $aliasList,

            'tahunTersedia' => $tahunTersedia,
            'tahunDipilih' => $tahunDipilih,
            'bulanAwal' => $bulanAwal,
            'bulanAkhir' => $bulanAkhir,
            'trenPerTahun' => $trenPerTahun,
            'jumlahBarisPerTahun' => $jumlahBarisPerTahun,
        ]);
    }

    /**
     * Total per bulan sesuai kolom satuan aktif (kali/kwh/rp), satu array per
     * tahun yang dipilih, dibatasi rentang bulan tertentu — dipakai untuk
     * grafik + tabel data "Visualisasi Tren Transaksi" (multi-tahun).
     *
     * Tahun yang ada di DATA_HISTORIS_KWH (dan kolom aktifnya energi_kwh serta
     * TIDAK sedang difilter per-SPKLU) diambil langsung dari constant, gak
     * query DB sama sekali — data itu emang gak pernah ada di tabel transaksis.
     */
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
        $mulai = $request->dari ? Carbon::parse($request->dari) : now()->subMonths(9);
        $sampai = $request->sampai ? Carbon::parse($request->sampai) : now();

        $rincian = Transaksi::query()
            ->with('spklu')
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->orderByDesc('tanggal')
            ->get();

        $pdf = \PDF::loadView('transaksi.export-pdf', compact('rincian', 'mulai', 'sampai'));

        return $pdf->download('rekap-transaksi-' . now()->format('Ymd-His') . '.pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:51200',
        ]);

        set_time_limit(600);

        $uploadedFile = $request->file('file');
        $namaFileAsli = $uploadedFile->getClientOriginalName();
        $ukuranBytes = $uploadedFile->getSize();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        Log::info('=== TRANSAKSI IMPORT START ===', ['file' => $namaFileAsli]);

        $uploadLog = TransaksiUpload::create([
            'nama_file' => $namaFileAsli,
            'ukuran_bytes' => $ukuranBytes,
            'status' => 'berhasil',
            'diupload_oleh' => $request->user()->id,
        ]);

        $this->simpanFileMentah($uploadedFile, $uploadLog);

        return $this->processImportFile($request, $uploadedFile->getRealPath(), $extension, $uploadLog, $namaFileAsli);
    }

    public function reupload(Request $request, TransaksiUpload $transaksiUpload)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:51200',
        ]);

        set_time_limit(600);

        $uploadedFile = $request->file('file');
        $namaFileAsli = $uploadedFile->getClientOriginalName();
        $ukuranBytes = $uploadedFile->getSize();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        Log::info('=== TRANSAKSI REUPLOAD (ganti file) START ===', [
            'upload_id' => $transaksiUpload->id,
            'file' => $namaFileAsli,
        ]);

        DB::transaction(function () use ($transaksiUpload) {
            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
        });

        if ($transaksiUpload->path_file) {
            Storage::delete($transaksiUpload->path_file);
        }

        $transaksiUpload->update([
            'nama_file' => $namaFileAsli,
            'ukuran_bytes' => $ukuranBytes,
            'status' => 'berhasil',
            'pesan_error' => null,
            'path_file' => null,
        ]);

        $this->simpanFileMentah($uploadedFile, $transaksiUpload);

        return $this->processImportFile($request, $uploadedFile->getRealPath(), $extension, $transaksiUpload, $namaFileAsli);
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

        set_time_limit(600);

        Log::info('=== TRANSAKSI PROSES ULANG (file tersimpan, tanpa upload baru) START ===', [
            'upload_id' => $transaksiUpload->id,
            'path_file' => $transaksiUpload->path_file,
        ]);

        DB::transaction(function () use ($transaksiUpload) {
            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
        });

        $transaksiUpload->update([
            'status' => 'berhasil',
            'pesan_error' => null,
        ]);

        $extension = strtolower(pathinfo($transaksiUpload->path_file, PATHINFO_EXTENSION));
        $pathAbsolut = Storage::path($transaksiUpload->path_file);

        return $this->processImportFile($request, $pathAbsolut, $extension, $transaksiUpload, $transaksiUpload->nama_file);
    }

    private function simpanFileMentah($uploadedFile, TransaksiUpload $uploadLog): void
    {
        try {
            $extensi = strtolower($uploadedFile->getClientOriginalExtension());
            $namaTersimpan = $uploadLog->id . '_' . now()->format('YmdHis') . '.' . $extensi;
            $path = $uploadedFile->storeAs('transaksi-uploads', $namaTersimpan);
            $uploadLog->update(['path_file' => $path]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan salinan file mentah', [
                'upload_id' => $uploadLog->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function processImportFile(Request $request, string $pathToImport, string $extension, TransaksiUpload $uploadLog, string $namaFileAsli)
    {
        $csvHasilConversi = null;
        $delimiter = ',';

        if (in_array($extension, ['xlsx', 'xls'])) {
            $converted = $this->convertToCsv($pathToImport);
            if ($converted) {
                $pathToImport = $converted;
                $csvHasilConversi = $converted;
            }
        } elseif ($extension === 'csv') {
            $pathToImport = $this->stripBomIfPresent($pathToImport);
            $delimiter = $this->detectCsvDelimiter($pathToImport);
        }

        $import = new TransaksiImport($delimiter);

        try {
            Excel::import($import, $pathToImport);
        } catch (\Throwable $e) {
            Log::error('EXCEPTION saat Excel::import()', ['message' => $e->getMessage()]);

            $uploadLog->update([
                'status' => 'gagal',
                'pesan_error' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'nama_file' => $namaFileAsli,
                    'message' => 'Gagal memproses file: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }

        if ($csvHasilConversi && file_exists($csvHasilConversi)) {
            @unlink($csvHasilConversi);
        }

        $totalTersimpan = $import->flushToDatabase($request->user()->id, $uploadLog->id);

        foreach ($import->unmatched as $nama => $jumlah) {
            $existing = TransaksiUnmatchedName::where('nama_asli', $nama)->first();
            if ($existing) {
                $existing->increment('jumlah_baris_total', $jumlah);
            } else {
                TransaksiUnmatchedName::create(['nama_asli' => $nama, 'jumlah_baris_total' => $jumlah]);
            }

            TransaksiUploadUnmatched::updateOrCreate(
                ['transaksi_upload_id' => $uploadLog->id, 'nama_asli' => $nama],
                ['jumlah_baris' => $jumlah]
            );
        }

        $uploadLog->update([
            'total_baris_diproses' => $import->totalRowsProcessed,
            'total_rekap_tersimpan' => $totalTersimpan,
            'jumlah_nama_tidak_cocok' => count($import->unmatched),
        ]);

        Log::info('=== TRANSAKSI IMPORT END ===', [
            'upload_id' => $uploadLog->id,
            'total_diproses' => $import->totalRowsProcessed,
            'total_tersimpan' => $totalTersimpan,
        ]);

        $pesan = "Berhasil memproses " . number_format($import->totalRowsProcessed) . " baris menjadi " . number_format($totalTersimpan) . " rekap harian.";

        if (count($import->unmatched) > 0) {
            $totalBarisGagal = array_sum($import->unmatched);
            $pesan .= " ⚠ " . count($import->unmatched) . " nama SPKLU (total " . number_format($totalBarisGagal) . " baris) tidak cocok.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'nama_file' => $namaFileAsli,
                'total_baris_diproses' => $import->totalRowsProcessed,
                'total_tersimpan' => $totalTersimpan,
                'unmatched_count' => count($import->unmatched),
                'message' => $pesan,
            ]);
        }

        return back()->with('success', $pesan);
    }

    public function storeAlias(Request $request)
    {
        $request->validate([
            'nama_asli' => 'required|string|unique:spklu_aliases,nama_asli',
            'spklu_id' => 'required|exists:spklus,id',
        ]);

        SpkluAlias::create([
            'nama_asli' => $request->nama_asli,
            'spklu_id' => $request->spklu_id,
            'dibuat_oleh' => $request->user()->id,
        ]);

        return back()->with('success', 'Alias berhasil disimpan. Upload ulang file yang sama untuk memproses baris yang tadinya terlewat.');
    }

    public function storeAliasBulk(Request $request)
    {
        $request->validate([
            'mappings' => 'required|array|min:1',
            'mappings.*.nama_asli' => 'required|string',
            'mappings.*.spklu_id' => 'required|exists:spklus,id',
        ]);

        $disimpan = 0;

        foreach ($request->mappings as $map) {
            SpkluAlias::updateOrCreate(
                ['nama_asli' => $map['nama_asli']],
                ['spklu_id' => $map['spklu_id'], 'dibuat_oleh' => $request->user()->id]
            );
            $disimpan++;
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'jumlah' => $disimpan]);
        }

        return back()->with('success', "{$disimpan} pemetaan alias berhasil disimpan sekaligus. Upload ulang file terkait untuk memproses baris yang tadinya terlewat.");
    }

    public function updateAlias(Request $request, SpkluAlias $spkluAlias)
    {
        $request->validate([
            'spklu_id' => 'required|exists:spklus,id',
        ]);

        $spkluAlias->update([
            'spklu_id' => $request->spklu_id,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "Pemetaan \"{$spkluAlias->nama_asli}\" berhasil diperbarui.");
    }

    public function destroyUpload(TransaksiUpload $transaksiUpload)
    {
        $namaFile = $transaksiUpload->nama_file;
        $pathFile = $transaksiUpload->path_file;

        DB::transaction(function () use ($transaksiUpload) {
            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
            $transaksiUpload->delete();
        });

        if ($pathFile) {
            Storage::delete($pathFile);
        }

        return back()->with('success', "Riwayat \"{$namaFile}\" dan seluruh data transaksi yang tersimpan dari file itu berhasil dihapus.");
    }

    private function convertToCsv(string $xlsxPath): ?string
    {
        $sofficeBinary = $this->findSofficeBinary();
        if (! $sofficeBinary) {
            return null;
        }

        $outputDir = storage_path('app/temp-imports');
        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $process = new Process([
            $sofficeBinary, '--headless', '--convert-to',
            'csv:Text - txt - csv (StarCalc):44,34,0,1,,,,,,,,-1',
            '--outdir', $outputDir, $xlsxPath,
        ]);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            return null;
        }

        $csvPath = $outputDir . DIRECTORY_SEPARATOR . pathinfo($xlsxPath, PATHINFO_FILENAME) . '.csv';
        return file_exists($csvPath) ? $csvPath : null;
    }

    private function findSofficeBinary(): ?string
    {
        foreach (['C:\Program Files\LibreOffice\program\soffice.exe', 'C:\Program Files (x86)\LibreOffice\program\soffice.exe', 'soffice'] as $path) {
            if ($path === 'soffice' || file_exists($path)) {
                return $path;
            }
        }
        return null;
    }

    private function detectCsvDelimiter(string $csvPath): string
    {
        $handle = fopen($csvPath, 'r');
        $firstLine = fgets($handle);
        fclose($handle);

        if (! $firstLine) {
            return ',';
        }

        $jumlahKoma = substr_count($firstLine, ',');
        $jumlahTitikKoma = substr_count($firstLine, ';');

        return $jumlahTitikKoma > $jumlahKoma ? ';' : ',';
    }

    private function stripBomIfPresent(string $csvPath): string
    {
        $handle = fopen($csvPath, 'rb');
        $firstBytes = fread($handle, 3);
        fclose($handle);

        $bom = "\xEF\xBB\xBF";
        if ($firstBytes !== $bom) {
            return $csvPath;
        }

        $content = file_get_contents($csvPath);
        $content = substr($content, 3);

        $tempPath = storage_path('app/temp-imports');
        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $newPath = $tempPath . DIRECTORY_SEPARATOR . 'nobom_' . basename($csvPath);
        file_put_contents($newPath, $content);

        return $newPath;
    }
}