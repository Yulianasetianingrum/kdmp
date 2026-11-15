<?php

namespace App\Http\Controllers;

use App\Models\Master\Komoditas;
use App\Models\Survei\ProdusenDesa;
use App\Models\Survei\RtDesa;
use App\Models\Survei\SesiSurvei;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ringkasan = [
            'jumlah_sesi' => SesiSurvei::count(),
            'jumlah_rt' => RtDesa::count(),
            'jumlah_produsen' => ProdusenDesa::count(),
            'jumlah_komoditas' => Komoditas::count(),
        ];

        return view('dashboard', compact('ringkasan'));
    }
}
