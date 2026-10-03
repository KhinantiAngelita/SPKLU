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
        $user = $request->user();
        $userUp3 = $user?->up3;
        $isSuperAdmin = $user?->role === 'super_admin';

        $selectedUp3 = $request->get('up3');
        if (! $isSuperAdmin && $userUp3) {
            $selectedUp3 = $userUp3;
        }

        $data = $this->proyeksiService->siapkanDataProyeksi($selectedUp3);

        return view('transaksi.proyeksi', [
            'asumsiTeks' => $data['asumsi_teks'],
            'growthYoyPersen' => $data['growth_yoy_persen'],
            'tarifPerKwh' => $data['tarif_per_kwh'],
            'kwhPerUnit' => $data['kwh_per_unit'],
            'skenarios' => $data['skenarios'],
            'periods' => $data['periods'],
            'hasDbData' => $data['has_db_data'],
            'periodsJson' => json_encode($data['periods']),
            'selectedUp3' => $selectedUp3,
        ]);
    }
}
