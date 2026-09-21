<?php

namespace App\Http\Controllers;

use App\Enums\SpkluStatus;
use App\Imports\SpkluImport;
use App\Models\Spklu;
use App\Models\SpkluAlias;
use App\Models\TransaksiUnmatchedName;
use App\Models\UlpMapping;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterSpkluController extends Controller
{
    public function index(Request $request)
    {
        $spklus = Spklu::query()
            ->with('ulp')
            ->aktif()
            ->when($request->search, fn ($q) => $q->where('nama', 'like', "%{$request->search}%"))
            ->when($request->ulp_id, fn ($q) => $q->where('ulp_mapping_id', $request->ulp_id))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->kepemilikan, fn ($q) => $q->where('kepemilikan', $request->kepemilikan))
            ->when($request->skema, fn ($q) => $q->where('skema', $request->skema))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Nama SPKLU dari file transaksi yang belum berhasil dipetakan ke Master SPKLU manapun.
        // Ditampilkan di sini juga (bukan cuma di halaman Transaksi) supaya Super Admin/Pengelola
        // yang lagi buka Master SPKLU ikut sadar ada data transaksi "menggantung" karena beda ejaan nama.
        $namaSudahDialias = SpkluAlias::pluck('nama_asli')->all();
        $unmatchedTransaksiCount = TransaksiUnmatchedName::whereNotIn('nama_asli', $namaSudahDialias)->count();

        return view('master-spklu.index', [
            'spklus' => $spklus,
            'ulpList' => UlpMapping::orderBy('nama_penuh')->get(),

            'totalUnit' => Spklu::aktif()->count(),
            'totalByType' => Spklu::aktif()->selectRaw('type, count(*) as jumlah')->groupBy('type')->pluck('jumlah', 'type'),
            'totalByKepemilikan' => Spklu::aktif()->selectRaw('kepemilikan, count(*) as jumlah')->groupBy('kepemilikan')->pluck('jumlah', 'kepemilikan'),
            'totalKapasitas' => Spklu::aktif()->sum('kw'),

            'menungguValidasiCount' => Spklu::menungguValidasi()->count(),
            'unmatchedTransaksiCount' => $unmatchedTransaksiCount,
        ]);
    }

    public function validasiIndex()
    {
        $pending = Spklu::menungguValidasi()->with('ulp')->latest()->get();

        return view('master-spklu.validasi', compact('pending'));
    }

    public function validasiApprove(Request $request, Spklu $spklu)
    {
        abort_unless($spklu->status === SpkluStatus::MenungguValidasi, 400);

        $spklu->update([
            'status' => SpkluStatus::Aktif,
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        return back()->with('success', "{$spklu->nama} berhasil divalidasi dan resmi aktif di Master SPKLU.");
    }

    public function validasiReject(Request $request, Spklu $spklu)
    {
        abort_unless($spklu->status === SpkluStatus::MenungguValidasi, 400);

        $request->validate(['alasan' => 'required|string']);

        $spklu->delete();

        return back()->with('success', "{$spklu->nama} ditolak dan dikembalikan ke status On Progress di Pengajuan.");
    }

    /**
     * BARU: dipanggil via AJAX dari form Tambah/Edit SPKLU saat dropdown ULP
     * diganti — nyariin kode_unit yang PALING SERING dipakai oleh SPKLU lain
     * di ULP yang sama (dalam praktiknya kode_unit itu kode kantor unit PLN
     * per wilayah kerja, jadi wajar semua SPKLU di 1 ULP punya kode yang
     * sama — tapi datanya tetap disimpan per-baris di tabel spklus, bukan
     * ditarik dari ulp_mappings, karena kolom itu emang gak ada di sana).
     * Pakai modus (nilai paling sering muncul) bukan cuma "ambil yang
     * pertama ketemu", biar tahan kalau ada 1-2 data lama yang salah ketik.
     */
    public function kodeUnitByUlp(UlpMapping $ulpMapping)
    {
        $kodeUnit = Spklu::where('ulp_mapping_id', $ulpMapping->id)
            ->whereNotNull('kode_unit')
            ->where('kode_unit', '!=', '')
            ->get(['kode_unit'])
            ->countBy('kode_unit')
            ->sortDesc()
            ->keys()
            ->first();

        return response()->json(['kode_unit' => $kodeUnit]);
    }

    /**
     * FIX: sebelumnya id_spklu di-generate dari `Spklu::withTrashed()->max('id') + 1`
     * — asumsinya id_spklu SELALU sinkron 1:1 dengan primary key `id` (baris ke-42
     * di tabel otomatis dianggap "SPKLU-043"). Asumsi ini pecah begitu ada data yang
     * masuk lewat Import Excel (lihat SpkluImport): di situ id_spklu diambil LANGSUNG
     * dari kolom "ID SPKLU" file Excel-nya, bukan diturunkan dari `id` tabel. Kalau
     * nomor di Excel udah "lompat" lebih jauh dibanding jumlah baris yang sebenarnya
     * ada di database, generate manual bisa nabrak nomor yang udah dipakai import
     * itu -> UniqueConstraintViolationException (SQLSTATE 23000, duplicate entry).
     *
     * Sekarang nomor urut diambil dari id_spklu TERBESAR yang BENERAN ada di
     * database (bukan dari kolom id), baru di-increment dari situ — jadi apapun
     * jalur data itu masuk (manual atau import), penomoran berikutnya selalu
     * nyambung dari kondisi data yang sebenarnya, bukan asumsi yang bisa meleset.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode_unit' => 'nullable|string|max:50',
            'ulp_mapping_id' => 'required|exists:ulp_mappings,id',
            'type' => 'required|in:AC,DC',
            'kw' => 'required|numeric|min:0',
            'nozzle' => 'required|integer|min:1',
            'kepemilikan' => 'required|in:PLN,Swasta',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Retry kecil buat jaga-jaga race condition murni (dua admin nambah
        // data SPKLU baru di detik yang sama persis) — bukan solusi utama,
        // cuma jaring pengaman tambahan di atas fix utamanya di atas.
        $percobaan = 0;

        while (true) {
            try {
                Spklu::create([
                    ...$validated,
                    'id_spklu' => $this->generateIdSpkluBerikutnya(),
                    'status' => SpkluStatus::Aktif,
                    'sumber' => 'manual',
                ]);
                break;
            } catch (UniqueConstraintViolationException $e) {
                $percobaan++;
                if ($percobaan >= 3) {
                    throw $e;
                }
            }
        }

        return back()->with('success', 'SPKLU baru berhasil ditambahkan.');
    }

    protected function generateIdSpkluBerikutnya(): string
    {
        $nomorTerakhir = Spklu::withTrashed()
            ->where('id_spklu', 'like', 'SPKLU-%')
            ->get(['id_spklu'])
            ->map(fn ($s) => (int) preg_replace('/\D/', '', $s->id_spklu))
            ->max();

        $nomorBerikutnya = ($nomorTerakhir ?? 0) + 1;

        return 'SPKLU-' . str_pad((string) $nomorBerikutnya, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Import banyak data SPKLU sekaligus dari file Excel/CSV.
     * Baris yang gagal validasi (misal nama ULP gak ketemu) di-skip,
     * bukan bikin seluruh proses batal — ringkasan kegagalan ditampilkan ke user.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new SpkluImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        if ($failures->count() > 0) {
            $detail = $failures->map(fn ($f) => "Baris {$f->row()}: " . implode(', ', $f->errors()))
                ->take(5)
                ->implode(' | ');

            return back()->with('error', "Import selesai, tapi {$failures->count()} baris gagal/dilewati — {$detail}" . ($failures->count() > 5 ? ' ...' : ''));
        }

        return back()->with('success', 'Data SPKLU berhasil diimpor dari Excel.');
    }

    public function update(Request $request, Spklu $spklu)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode_unit' => 'nullable|string|max:50',
            'ulp_mapping_id' => 'required|exists:ulp_mappings,id',
            'type' => 'required|in:AC,DC',
            'kw' => 'nullable|numeric|min:0',
            'kw_detail' => 'nullable|string',
            'nozzle' => 'required|integer|min:1',
            'kepemilikan' => 'required|in:PLN,Swasta',
            'skema' => 'nullable|integer',
            'tanggal_aktif' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $spklu->update($validated);

        return back()->with('success', "{$spklu->nama} berhasil diperbarui.");
    }
}