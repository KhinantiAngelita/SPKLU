<?php

namespace App\Http\Controllers;

use App\Imports\TransaksiImport;
use App\Models\Spklu;
use App\Models\SpkluAlias;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

        $mulai = $request->dari ? \Carbon\Carbon::parse($request->dari) : now()->subMonths(9);
        $sampai = $request->sampai ? \Carbon\Carbon::parse($request->sampai) : now();

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

        return view('transaksi.index', [
            'chartData' => $data,
            'spkluList' => Spklu::aktif()->orderBy('nama')->get(),
            'spkluTerpilih' => $spkluId,
            'satuan' => $satuan,
            'tampilan' => $tampilan,
            'dari' => $mulai->format('Y-m-d'),
            'sampai' => $sampai->format('Y-m-d'),
            'aliasList' => SpkluAlias::with('spklu')->latest()->get(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:51200', // sampai 50MB, file transaksi bisa besar
        ]);

        set_time_limit(600); // proses ratusan ribu baris butuh lebih dari waktu default

        $import = new TransaksiImport;
        Excel::import($import, $request->file('file'));

        $totalTersimpan = $import->flushToDatabase($request->user()->id);

        $pesan = "Berhasil memproses " . number_format($import->totalRowsProcessed) . " baris transaksi menjadi " . number_format($totalTersimpan) . " rekap harian per SPKLU.";

        if (count($import->unmatched) > 0) {
            $totalBarisGagal = array_sum($import->unmatched);
            session()->flash('unmatched_spklu', $import->unmatched);
            $pesan .= " ⚠ " . count($import->unmatched) . " nama SPKLU (total " . number_format($totalBarisGagal) . " baris) tidak cocok dan dilewati — cek daftar di bawah untuk buat pemetaan manual.";
        }

        return back()->with('success', $pesan);
    }

    /** Super Admin/Pengelola petain manual nama SPKLU di file -> SPKLU asli di sistem. */
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
}