<?php

namespace App\Http\Controllers;

use App\Enums\SpkluStatus;
use App\Imports\SpkluImport;
use App\Models\Spklu;
use App\Models\UlpMapping;
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

        return view('master-spklu.index', [
            'spklus' => $spklus,
            'ulpList' => UlpMapping::orderBy('nama_penuh')->get(),

            'totalUnit' => Spklu::aktif()->count(),
            'totalByType' => Spklu::aktif()->selectRaw('type, count(*) as jumlah')->groupBy('type')->pluck('jumlah', 'type'),
            'totalByKepemilikan' => Spklu::aktif()->selectRaw('kepemilikan, count(*) as jumlah')->groupBy('kepemilikan')->pluck('jumlah', 'kepemilikan'),
            'totalKapasitas' => Spklu::aktif()->sum('kw'),

            'menungguValidasiCount' => Spklu::menungguValidasi()->count(),
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ulp_mapping_id' => 'required|exists:ulp_mappings,id',
            'type' => 'required|in:AC,DC',
            'kw' => 'required|numeric|min:0',
            'nozzle' => 'required|integer|min:1',
            'kepemilikan' => 'required|in:PLN,Swasta',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        Spklu::create([
            ...$validated,
            'id_spklu' => 'SPKLU-' . str_pad((string) (Spklu::withTrashed()->max('id') + 1), 3, '0', STR_PAD_LEFT),
            'status' => SpkluStatus::Aktif,
            'sumber' => 'manual',
        ]);

        return back()->with('success', 'SPKLU baru berhasil ditambahkan.');
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
            'ulp_mapping_id' => 'required|exists:ulp_mappings,id',
            'type' => 'required|in:AC,DC',
            'kw' => 'nullable|numeric|min:0',
            'kw_detail' => 'nullable|string',
            'nozzle' => 'required|integer|min:1',
            'kepemilikan' => 'required|in:PLN,Swasta',
            'skema' => 'nullable|integer',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $spklu->update($validated);

        return back()->with('success', "{$spklu->nama} berhasil diperbarui.");
    }
}