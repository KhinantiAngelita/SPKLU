<?php

namespace App\Http\Controllers;

use App\Enums\SpkluStatus;
use App\Helpers\NotifikasiHelper;
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

            // Mengirim data Alias dan Unmatched ke Halaman Master SPKLU
            'aliasList' => SpkluAlias::with('spklu')->latest()->get(),
            'unmatchedList' => TransaksiUnmatchedName::whereNotIn('nama_asli', $namaSudahDialias)
                ->orderByDesc('jumlah_baris_total')
                ->get(),
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

        $dataUpdate = [
            'status' => SpkluStatus::Aktif,
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ];

        if (empty($spklu->kode_unit) && $spklu->ulp_mapping_id) {
            $dataUpdate['kode_unit'] = Spklu::where('ulp_mapping_id', $spklu->ulp_mapping_id)
                ->whereNotNull('kode_unit')
                ->where('kode_unit', '!=', '')
                ->get(['kode_unit'])
                ->countBy('kode_unit')
                ->sortDesc()
                ->keys()
                ->first();
        }

        $spklu->update($dataUpdate);

        NotifikasiHelper::kirim(
            'spklu',
            "SPKLU \"{$spklu->nama}\" resmi divalidasi dan aktif di Master SPKLU.",
            'check-circle-2',
            route('master-spklu.index', ['search' => $spklu->nama]),
            null,
            'SPKLU Resmi Aktif'
        );

        return back()->with('success', "{$spklu->nama} berhasil divalidasi dan resmi aktif di Master SPKLU.");
    }

    public function validasiReject(Request $request, Spklu $spklu)
    {
        abort_unless($spklu->status === SpkluStatus::MenungguValidasi, 400);

        $request->validate(['alasan' => 'required|string']);

        $spklu->delete();

        return back()->with('success', "{$spklu->nama} ditolak dan dikembalikan ke status On Progress di Pengajuan.");
    }

    public function kodeUnitByUlp(UlpMapping $ulpMapping)
    {
        $kodeUnit = $ulpMapping->kode_unit ?? Spklu::where('ulp_mapping_id', $ulpMapping->id)
            ->whereNotNull('kode_unit')
            ->where('kode_unit', '!=', '')
            ->get(['kode_unit'])
            ->countBy('kode_unit')
            ->sortDesc()
            ->keys()
            ->first();

        return response()->json(['kode_unit' => $kodeUnit]);
    }

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

        if (empty($validated['kode_unit'])) {
            $validated['kode_unit'] = Spklu::where('ulp_mapping_id', $validated['ulp_mapping_id'])
                ->whereNotNull('kode_unit')
                ->where('kode_unit', '!=', '')
                ->get(['kode_unit'])
                ->countBy('kode_unit')
                ->sortDesc()
                ->keys()
                ->first();
        }

        $percobaan = 0;

        while (true) {
            try {
                $baru = Spklu::create([
                    ...$validated,
                    'id_spklu' => $this->generateIdSpkluBerikutnya(),
                    'status' => SpkluStatus::Aktif,
                    'sumber' => 'manual',
                ]);

                NotifikasiHelper::kirim(
                    'spklu',
                    "SPKLU baru \"{$baru->nama}\" berhasil ditambahkan.",
                    'zap',
                    route('master-spklu.index', ['search' => $baru->nama]),
                    null,
                    'SPKLU Baru Ditambahkan'
                );

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

        return 'SPKLU-'.str_pad((string) $nomorBerikutnya, 3, '0', STR_PAD_LEFT);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new SpkluImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        if ($failures->count() > 0) {
            $detail = $failures->map(fn ($f) => "Baris {$f->row()}: ".implode(', ', $f->errors()))
                ->take(5)
                ->implode(' | ');

            return back()->with('error', "Import selesai, tapi {$failures->count()} baris gagal/dilewati — {$detail}".($failures->count() > 5 ? ' ...' : ''));
        }

        NotifikasiHelper::kirim(
            'spklu',
            'Data Master SPKLU berhasil diimpor dari file Excel.',
            'file-spreadsheet',
            route('master-spklu.index'),
            null,
            'Import SPKLU Selesai'
        );

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

        $ulpBaru = UlpMapping::find($validated['ulp_mapping_id']);
        if ($ulpBaru && $ulpBaru->kode_unit) {
            // Jika ULP berubah, atau kode_unit kosong, otomatis sesuaikan ke kode unit resmi ULP baru
            if ((int) $spklu->ulp_mapping_id !== (int) $validated['ulp_mapping_id'] || empty($validated['kode_unit'])) {
                $validated['kode_unit'] = $ulpBaru->kode_unit;
            }
        }

        $spklu->update($validated);

        NotifikasiHelper::kirim(
            'spklu',
            "Data SPKLU \"{$spklu->nama}\" telah diperbarui.",
            'zap',
            route('master-spklu.index', ['search' => $spklu->nama]),
            null,
            'Pembaruan Master SPKLU'
        );

        return back()->with('success', "{$spklu->nama} berhasil diperbarui.");
    }

    // METHOD PEMETAAN ALIAS (DIPINDAH DARI TRANSAKSI)
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

        return back()->with('success', 'Alias berhasil disimpan.');
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

        return back()->with('success', "{$disimpan} pemetaan alias berhasil disimpan sekaligus.");
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
}
