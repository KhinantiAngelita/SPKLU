<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Spklu;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalSpkluTerpasang' => Spklu::aktif()->count(),
            'spkluBaruBulanIni' => Spklu::aktif()->whereMonth('created_at', now()->month)->count(),

            'pengajuanOnProgress' => 0,
            'kandidatAktif' => 0,
            'kandidatButuhTindakLanjut' => 0,

            'jadwalHariIni' => Jadwal::whereDate('waktu_mulai', today())->orderBy('waktu_mulai')->get(),
            'jadwalBesok' => Jadwal::whereDate('waktu_mulai', today()->addDay())->orderBy('waktu_mulai')->get(),
            'kalenderBulanIni' => $this->buildKalenderData(),

            'topKandidat' => [], // nanti diisi array of object {nama, wilayah, skor} dari Service Person B

            'trenTransaksiPersen' => 0,
            'trenTransaksiLabels' => [],
            'trenTransaksiData' => [],
        ]);
    }

    private function buildKalenderData(): array
    {
        $bulan = now();
        $jadwalBulanIni = Jadwal::whereMonth('waktu_mulai', $bulan->month)
            ->whereYear('waktu_mulai', $bulan->year)
            ->get()
            ->groupBy(fn ($j) => $j->waktu_mulai->format('j'));

        return [
            'bulan' => $bulan->translatedFormat('F Y'),
            'tanggalBerjadwal' => $jadwalBulanIni->keys()->toArray(),
        ];
    }
}