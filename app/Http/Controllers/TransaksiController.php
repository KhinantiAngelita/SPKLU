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
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $spkluId = $request->spklu_id;
        $satuan = $request->satuan ?? 'kali';
        $tampilan = $request->tampilan ?? 'bulanan';

        $kolom = match ($satuan) {
            'kwh' => 'energi_kwh',
            'rp' => 'pendapatan_rp',
            default => 'jumlah_transaksi',
        };

        $mulai = $request->dari ? Carbon::parse($request->dari) : now()->subMonths(9);
        $sampai = $request->sampai ? Carbon::parse($request->sampai) : now();

        $data = Transaksi::query()
            ->when($spkluId, fn ($q) => $q->where('spklu_id', $spkluId))
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM({$kolom}) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        if ($tampilan === 'kumulatif') {
            $running = 0;
            $data = $data->map(function ($row) use (&$running) {
                $running += $row->total;
                $row->total = $running;
                return $row;
            });
        }

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

        return view('transaksi.index', [
            'chartData' => $data,
            'spkluList' => Spklu::aktif()->orderBy('nama')->get(),
            'spkluTerpilih' => $spkluId,
            'satuan' => $satuan,
            'tampilan' => $tampilan,
            'dari' => $mulai->format('Y-m-d'),
            'sampai' => $sampai->format('Y-m-d'),

            'totalTransaksi' => $totalTransaksi,
            'totalEnergi' => $totalEnergi,
            'totalPendapatan' => $totalPendapatan,
            'rataRataKwhPerTransaksi' => $rataRataKwhPerTransaksi,
            'trend' => $trend,
            'rincian' => $rincian,
            'aliasList' => $aliasList,
        ]);
    }

    public function uploadPage()
    {
        $namaSudahDialias = SpkluAlias::pluck('nama_asli')->all();

        $riwayat = TransaksiUpload::with(['diuploadOleh', 'unmatchedNames'])->latest()->paginate(15);

        // Buat tiap baris riwayat: hitung nama yang MASIH belum dipetakan, khusus dari file itu
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

        Log::info('=== TRANSAKSI IMPORT START ===', ['file' => $namaFileAsli]);

        $uploadLog = TransaksiUpload::create([
            'nama_file' => $namaFileAsli,
            'ukuran_bytes' => $ukuranBytes,
            'status' => 'berhasil',
            'diupload_oleh' => $request->user()->id,
        ]);

        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        $pathToImport = $uploadedFile->getRealPath();
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

        // Simpan nama tidak cocok: akumulasi GLOBAL + catatan SPESIFIK per file ini
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

    public function destroyUpload(TransaksiUpload $transaksiUpload)
    {
        $namaFile = $transaksiUpload->nama_file;

        DB::transaction(function () use ($transaksiUpload) {
            $transaksiUpload->transaksis()->delete();
            $transaksiUpload->unmatchedNames()->delete();
            $transaksiUpload->delete();
        });

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