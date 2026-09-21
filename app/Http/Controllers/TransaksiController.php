<?php

namespace App\Http\Controllers;

use App\Jobs\ProsesImportTransaksiJob;
use App\Models\Spklu;
use App\Models\SpkluAlias;
use App\Models\Transaksi;
use App\Models\TransaksiUnmatchedName;
use App\Models\TransaksiUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
     * ALUR BARU: file disimpan PERMANEN dulu di sini (synchronous, masih
     * dalam 1 request HTTP), BARU dispatch job ke queue dengan membawa
     * ID upload-nya doang (bukan file/path temp). Job nanti ambil balik
     * path permanennya sendiri dari kolom path_file pas dia jalan.
     *
     * Kenapa harus gini: file temporary upload PHP OTOMATIS KEHAPUS begitu
     * response HTTP ini selesai dikirim — padahal job di queue baru jalan
     * belakangan (proses/request terpisah). Kalau job dikasih path temp,
     * pas dia jalan filenya udah gak ada lagi (`getRealPath()` jadi
     * null/false) — itu penyebab error "Argument #1 ($path) ... null
     * given" yang muncul sebelumnya.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:51200',
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

        // Simpan file PERMANEN dulu — WAJIB berhasil sebelum dispatch job,
        // kalau gagal jangan lanjut dispatch job yang nanti gak ada filenya.
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
            'file' => 'required|file|mimes:xlsx,xls,csv|max:51200',
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

    /**
     * Simpan salinan PERMANEN dari file yang baru diupload — sekarang ini
     * satu-satunya tempat file "hidup" sebelum job queue jalan, jadi kalau
     * ini gagal, import() TIDAK BOLEH lanjut dispatch job (lihat pemanggil).
     * Return bool (bukan void lagi) supaya pemanggil tau harus stop atau
     * lanjut.
     */
    private function simpanFileMentah($uploadedFile, TransaksiUpload $uploadLog): bool
    {
        try {
            $extensi = strtolower($uploadedFile->getClientOriginalExtension());
            $namaTersimpan = $uploadLog->id . '_' . now()->format('YmdHis') . '.' . $extensi;
            $path = $uploadedFile->storeAs('transaksi-uploads', $namaTersimpan);

            if (! $path) {
                Log::error('simpanFileMentah: storeAs() balikin false/null', ['upload_id' => $uploadLog->id]);
                return false;
            }

            $uploadLog->update(['path_file' => $path]);
            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan salinan file mentah', [
                'upload_id' => $uploadLog->id,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * FIX: sebelumnya destroyUpload()/reprocess()/reupload() cuma hapus
     * baris TransaksiUploadUnmatched (per-upload) tapi TIDAK PERNAH
     * ngurangin balik akumulasi global di TransaksiUnmatchedName
     * (jumlah_baris_total) — itu sumber data buat card "Nama SPKLU Belum
     * Dipetakan" di halaman upload. Efeknya, nama yang belum sempat
     * dipetakan tetap nyangkut muncul di card walau riwayat upload
     * sumbernya udah dihapus/diganti/diproses ulang, karena gak ada
     * upload lain yang "punya" kontribusi itu tapi angkanya tetap ada.
     *
     * WAJIB dipanggil SEBELUM $transaksiUpload->unmatchedNames()->delete()
     * — perlu baca rincian per-upload dulu sebelum baris itu hilang, buat
     * tau berapa yang harus dikurangi dari total global per nama.
     */
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
                // Gak ada upload lain yang masih nyumbang nama ini —
                // hapus sekalian record globalnya biar gak nyangkut di card.
                $global->delete();
            } else {
                $global->update(['jumlah_baris_total' => $sisaBaris]);
            }
        }
    }

    /**
     * Dipoll dari frontend (halaman Upload) buat cek status import yang
     * lagi jalan di background lewat queue — biar UI bisa auto-refresh
     * tanpa user manual reload page.
     */
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

    /**
     * FIX (lihat kurangiAkumulasiUnmatchedGlobal): sebelumnya method ini
     * cuma hapus transaksis() + unmatchedNames() (per-upload) + record
     * upload-nya sendiri, TANPA ngurangin akumulasi global
     * TransaksiUnmatchedName — itu penyebab card "Nama SPKLU Belum
     * Dipetakan" tetap nampilin nama dari upload yang udah dihapus.
     */
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