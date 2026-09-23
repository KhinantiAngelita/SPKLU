<?php

namespace App\Http\Controllers;

use App\Services\ProyeksiEnergiService;
use Illuminate\Http\Request;

class ProyeksiEnergiController extends Controller
{
    public function __construct(
        protected ProyeksiEnergiService $proyeksiService
    ) {}

    public function index(Request $request)
    {
        $data = $this->proyeksiService->siapkanDataProyeksi();

        return view('transaksi.proyeksi', [
            'asumsiTeks' => $data['asumsi_teks'],
            'growthYoyPersen' => $data['growth_yoy_persen'],
            'tarifPerKwh' => $data['tarif_per_kwh'],
            'kwhPerUnit' => $data['kwh_per_unit'],
            'skenarios' => $data['skenarios'],
            'periods' => $data['periods'],
            'hasDbData' => $data['has_db_data'],
            'periodsJson' => json_encode($data['periods']),
        ]);
    }
}
