<?php

namespace App\Services;

use App\Imports\TransaksiImport;
use App\Models\AktivitasNotifikasi;
use App\Models\TransaksiUnmatchedName;
use App\Models\TransaksiUpload;
use App\Models\TransaksiUploadUnmatched;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Process;

class TransaksiImportService
{
    /**
     * Proses inti import Excel/CSV jadi rekap harian di tabel transaksis.
     * Dipanggil dari ProsesImportTransaksiJob (background/queue) — TIDAK
     * lagi dipanggil langsung dari controller dalam 1 request HTTP, biar
     * gak kena timeout web server buat file besar di shared hosting.
     *
     * PENTING: $pathToImport WAJIB path file yang udah PERMANEN tersimpan
     * (hasil storeAs(), bisa diambil balik pakai Storage::path()) — BUKAN
     * path file temporary upload ($uploadedFile->getRealPath()). File temp
     * PHP otomatis kehapus begitu HTTP request kelar, padahal job ini baru
     * jalan belakangan di proses queue yang terpisah — kalau path yang
     * dikirim itu path temp, pas job jalan file-nya udah gak ada lagi.
     */
    public function proses(TransaksiUpload $uploadLog, string $pathToImport, string $extension, int $userId): void
    {
        $csvHasilConversi = null;
        $delimiter = ',';
        $readerType = ExcelFormat::XLSX;

        if (in_array($extension, ['xlsx', 'xls'])) {
            $converted = $this->convertToCsv($pathToImport);
            if ($converted) {
                $pathToImport = $converted;
                $csvHasilConversi = $converted;
                $readerType = ExcelFormat::CSV;
            } else {
                $readerType = $extension === 'xls' ? ExcelFormat::XLS : ExcelFormat::XLSX;
            }
        } elseif ($extension === 'csv') {
            $pathToImport = $this->stripBomIfPresent($pathToImport);
            $delimiter = $this->detectCsvDelimiter($pathToImport);
            $readerType = ExcelFormat::CSV;
        }

        $import = new TransaksiImport($delimiter, $uploadLog->id);

        try {
            Excel::import($import, $pathToImport, null, $readerType);
        } catch (\Throwable $e) {
            Log::error('EXCEPTION saat Excel::import() (job)', [
                'upload_id' => $uploadLog->id,
                'message' => $e->getMessage(),
            ]);

            $uploadLog->update([
                'status' => 'gagal',
                'pesan_error' => $e->getMessage(),
            ]);

            return;
        }

        if ($csvHasilConversi && file_exists($csvHasilConversi)) {
            @unlink($csvHasilConversi);
        }

        $totalTersimpan = $import->flushToDatabase($userId, $uploadLog->id);

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
            'status' => 'berhasil',
            'pesan_error' => null,
            'total_baris_diproses' => $import->totalRowsProcessed,
            'total_rekap_tersimpan' => $totalTersimpan,
            'jumlah_nama_tidak_cocok' => count($import->unmatched),
        ]);

        AktivitasNotifikasi::create([
            'user_id' => $userId,
            'kategori' => 'transaksi',
            'judul' => 'Import Transaksi Selesai',
            'pesan' => "File transaksi \"{$uploadLog->nama_file}\" berhasil diproses: {$totalTersimpan} ringkasan transaksi tersimpan.",
            'icon' => 'arrow-left-right',
            'url' => route('transaksi.index'),
            'target_roles' => null,
        ]);

        Log::info('=== TRANSAKSI IMPORT (job) SELESAI ===', [
            'upload_id' => $uploadLog->id,
            'total_diproses' => $import->totalRowsProcessed,
            'total_tersimpan' => $totalTersimpan,
        ]);
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

        $csvPath = $outputDir.DIRECTORY_SEPARATOR.pathinfo($xlsxPath, PATHINFO_FILENAME).'.csv';

        return file_exists($csvPath) ? $csvPath : null;
    }

    /**
     * FIX: sebelumnya ada bug di kondisi loop — entry terakhir ('soffice'
     * tanpa path absolut) selalu dianggap "ketemu" lewat pengecekan
     * `$path === 'soffice'` yang selalu true, TANPA benar-benar mengecek
     * file_exists(). Efeknya, di server yang gak punya LibreOffice sama
     * sekali, fungsi ini tetap balikin 'soffice' (bukan null), lalu
     * convertToCsv() maksa nyoba spawn command yang gak ada — bikin job
     * jalan lama/nyangkut sampai kena MaxAttemptsExceededException di
     * queue worker. Sekarang murni file_exists() semua, jadi balikin
     * null kalau LibreOffice beneran gak ada di server manapun.
     */
    private function findSofficeBinary(): ?string
    {
        foreach ([
            'C:\Program Files\LibreOffice\program\soffice.exe',
            'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
            '/usr/bin/soffice',
            '/usr/bin/libreoffice',
        ] as $path) {
            if (file_exists($path)) {
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

        $newPath = $tempPath.DIRECTORY_SEPARATOR.'nobom_'.basename($csvPath);
        file_put_contents($newPath, $content);

        return $newPath;
    }
}
