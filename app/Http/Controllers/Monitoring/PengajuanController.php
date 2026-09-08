<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;

class PengajuanController extends Controller
{
    public function index()
    {
        return view('monitoring.pengajuan.index');
    }
}