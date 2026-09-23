<?php

namespace App\Services;

use App\Models\TarifListrik;
use App\Models\Transaksi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProyeksiEnergiService
{
    /**
     * Estimasi konsumsi rata-rata per unit SPKLU per bulan (kWh).
     * Asumsi: mesin 50 kW x rerata 5 jam operasi per hari x 30 hari = 7.500 kWh.
     */
    public const KWH_PER_UNIT_BULAN = 7500;

    /**
     * Skenario preset pertumbuhan tahunan (YoY).
     */
    public const SKENARIO_PRESET = [
        'konservatif' => [
            'key' => 'konservatif',
            'label' => 'Konservatif',
            'yoy' => 150.0,
            'icon' => 'shield',
            'desc' => 'Adopsi EV landai (+150% YoY)',
        ],
        'moderat' => [
            'key' => 'moderat',
            'label' => 'Moderat (Baseline)',
            'yoy' => 315.0,
            'icon' => 'scale',
            'desc' => 'Tren benchmark resmi (+315% YoY)',
        ],
        'agresif' => [
            'key' => 'agresif',
            'label' => 'Agresif',
            'yoy' => 450.0,
            'icon' => 'rocket',
            'desc' => 'Akselerasi adopsi cepat (+450% YoY)',
        ],
    ];

    /**
     * Data benchmark resmi sesuai slide presentasi Danantara / PLN Electricity Services.
     * Digunakan sebagai dataset awal / preset default.
     */
    public const BENCHMARK_DATA = [
        'growth_yoy_persen' => 315.0,
        'asumsi_teks' => 'Penjualan energi eksisting diproyeksikan naik sesuai pertumbuhan realisasi (+315% YoY), ditambah kontribusi SPKLU baru (dengan asumsi mulai M+1).',
        'periods' => [
            [
                'key' => '2026-08',
                'bulan_label' => 'Ags 2026',
                'tipe' => 'realisasi',
                'spklu_baru_kwh' => null,
                'eksisting_kwh' => 579436,
                'total_kwh' => 579436,
            ],
            [
                'key' => '2026-09',
                'bulan_label' => 'Sep 2026',
                'tipe' => 'realisasi',
                'spklu_baru_kwh' => null,
                'eksisting_kwh' => 746596,
                'total_kwh' => 746596,
            ],
            [
                'key' => '2026-10',
                'bulan_label' => 'Okt 2026',
                'tipe' => 'proyeksi',
                'spklu_baru_kwh' => null,
                'eksisting_kwh' => 823811,
                'total_kwh' => 823811,
            ],
            [
                'key' => '2026-11',
                'bulan_label' => 'Nov 2026',
                'tipe' => 'proyeksi',
                'spklu_baru_kwh' => 50000,
                'eksisting_kwh' => 847309,
                'total_kwh' => 897309,
            ],
            [
                'key' => '2026-12',
                'bulan_label' => 'Des 2026',
                'tipe' => 'proyeksi',
                'spklu_baru_kwh' => 125000,
                'eksisting_kwh' => 924952,
                'total_kwh' => 1049952,
            ],
            [
                'key' => '2027-01',
                'bulan_label' => 'Jan 2027',
                'tipe' => 'proyeksi',
                'spklu_baru_kwh' => 275000,
                'eksisting_kwh' => 1160330,
                'total_kwh' => 1435330,
            ],
            [
                'key' => '2027-02',
                'bulan_label' => 'Feb 2027',
                'tipe' => 'proyeksi',
                'spklu_baru_kwh' => 650000,
                'eksisting_kwh' => 1308366,
                'total_kwh' => 1958366,
            ],
        ],
    ];

    /**
     * Ambil data realisasi bulanan dari tabel transaksi di database.
     *
     * @return Collection<string, float> Key: 'YYYY-MM', Value: total energi_kwh
     */
    public function ambilRealisasiBulananDb(): Collection
    {
        if (! Schema::hasTable('transaksis')) {
            return collect();
        }

        $driver = DB::connection()->getDriverName();
        $formatSql = $driver === 'sqlite'
            ? "strftime('%Y-%m', tanggal)"
            : "DATE_FORMAT(tanggal, '%Y-%m')";

        return Transaksi::query()
            ->selectRaw("{$formatSql} as periode, SUM(energi_kwh) as total_kwh")
            ->whereNotNull('tanggal')
            ->groupBy('periode')
            ->orderBy('periode')
            ->pluck('total_kwh', 'periode');
    }

    /**
     * Ambil tarif listrik SPKLU per kWh dari database master parameter (default TR / fallback Rp 2.466).
     */
    public function ambilTarifListrik(): float
    {
        if (! Schema::hasTable('tarif_listrik')) {
            return 2466.0;
        }

        $tarif = TarifListrik::where('kode', 'TR')->value('tarif_per_kwh');

        return $tarif ? (float) $tarif : 2466.0;
    }

    /**
     * Siapkan payload lengkap untuk view.
     *
     * @return array<string, mixed>
     */
    public function siapkanDataProyeksi(): array
    {
        $realisasiDb = $this->ambilRealisasiBulananDb();
        $tarifPerKwh = $this->ambilTarifListrik();
        $benchmark = self::BENCHMARK_DATA;

        // Cek apakah ada data transaksi riil di DB untuk bulan-bulan realisasi (misal 2026-08, 2026-09)
        // Jika ada, kita berikan data riil; jika kosong, fallback ke angka benchmark slide.
        $periods = collect($benchmark['periods'])->map(function (array $p) use ($realisasiDb, $tarifPerKwh) {
            $key = $p['key'];
            if ($p['tipe'] === 'realisasi' && isset($realisasiDb[$key]) && $realisasiDb[$key] > 0) {
                $p['eksisting_kwh'] = round((float) $realisasiDb[$key]);
                $p['total_kwh'] = $p['eksisting_kwh'];
                $p['sumber'] = 'database';
            } else {
                $p['sumber'] = 'benchmark';
            }

            // Estimasi jumlah unit SPKLU baru
            $p['spklu_baru_unit'] = $p['spklu_baru_kwh'] !== null
                ? (int) round($p['spklu_baru_kwh'] / self::KWH_PER_UNIT_BULAN)
                : null;

            // Hitung nilai Rupiah
            $p['eksisting_rp'] = round($p['eksisting_kwh'] * $tarifPerKwh);
            $p['spklu_baru_rp'] = $p['spklu_baru_kwh'] !== null ? round($p['spklu_baru_kwh'] * $tarifPerKwh) : null;
            $p['total_rp'] = round($p['total_kwh'] * $tarifPerKwh);

            return $p;
        })->all();

        return [
            'asumsi_teks' => $benchmark['asumsi_teks'],
            'growth_yoy_persen' => $benchmark['growth_yoy_persen'],
            'tarif_per_kwh' => $tarifPerKwh,
            'kwh_per_unit' => self::KWH_PER_UNIT_BULAN,
            'skenarios' => self::SKENARIO_PRESET,
            'periods' => $periods,
            'has_db_data' => $realisasiDb->isNotEmpty(),
            'total_realisasi_db_records' => $realisasiDb->count(),
        ];
    }
}
