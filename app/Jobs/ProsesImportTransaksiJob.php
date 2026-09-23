<?php

namespace App\Jobs;

use App\Models\TransaksiUpload;
use App\Services\TransaksiImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProsesImportTransaksiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 4000; // sedikit di bawah retry_after (4200 detik)

    public function __construct(
        public int $transaksiUploadId,
        public string $extension,
        public int $userId,
    ) {}

    /**
     * PENTING: path file diambil balik di SINI (pas job beneran jalan),
     * dari $uploadLog->path_file yang udah PERMANEN tersimpan — bukan
     * dikirim lewat constructor dari path temp upload. Itu penyebab bug
     * "null given" sebelumnya: path temp dikirim ke job, tapi filenya
     * udah kehapus duluan sama PHP pas request kelar sebelum job sempat
     * jalan di proses queue yang terpisah.
     *
     * ini_set memory_limit di awal: default PHP CLI di hosting ini cuma
     * 128M, gak cukup buat baca file Excel 100rb+ baris sekaligus —
     * proses ke-KILL paksa sama OS (OOM) tanpa exception/log apapun dari
     * PHP. Dinaikkan ke 1024M khusus buat proses job ini aja (gak
     * ngaruh ke request web biasa yang tetap pakai limit default).
     */
    public function handle(TransaksiImportService $service): void
    {
        ini_set('memory_limit', '1024M');
        @set_time_limit(4000);

        $uploadLog = TransaksiUpload::find($this->transaksiUploadId);

        if (! $uploadLog) {
            Log::error('ProsesImportTransaksiJob: TransaksiUpload tidak ditemukan', [
                'transaksi_upload_id' => $this->transaksiUploadId,
            ]);

            return;
        }

        if (! $uploadLog->path_file || ! Storage::exists($uploadLog->path_file)) {
            Log::error('ProsesImportTransaksiJob: file permanen tidak ditemukan di storage', [
                'transaksi_upload_id' => $this->transaksiUploadId,
                'path_file' => $uploadLog->path_file,
            ]);

            $uploadLog->update([
                'status' => 'gagal',
                'pesan_error' => 'File yang diupload tidak ditemukan lagi di server saat proses dimulai.',
            ]);

            return;
        }

        $pathAbsolut = Storage::path($uploadLog->path_file);

        Log::info('=== ProsesImportTransaksiJob MULAI ===', [
            'transaksi_upload_id' => $this->transaksiUploadId,
            'path_file' => $uploadLog->path_file,
        ]);

        $service->proses($uploadLog, $pathAbsolut, $this->extension, $this->userId);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProsesImportTransaksiJob GAGAL TOTAL (exception gak ketangkep di handle)', [
            'transaksi_upload_id' => $this->transaksiUploadId,
            'message' => $exception->getMessage(),
        ]);

        TransaksiUpload::where('id', $this->transaksiUploadId)->update([
            'status' => 'gagal',
            'pesan_error' => 'Job queue gagal: '.$exception->getMessage(),
        ]);
    }
}
