<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\ObjekPajak;
use App\Models\Pembayaran;
use App\Models\Sppt;
use App\Models\SubjekPajak;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'summary' => [
                'subjek' => SubjekPajak::count(),
                'objek' => ObjekPajak::count(),
                'sppt' => Sppt::count(),
                'mutasi' => Mutasi::count(),
                'pembayaran' => Pembayaran::count(),
                'unpaid_sppt' => Sppt::where('status_bayar', 'piutang')->count(),
                'total_pembayaran' => Pembayaran::sum('jumlah_bayar'),
                'total_terhutang' => Sppt::where('status_bayar', 'piutang')->sum('pajak_terhutang'),
            ],
            'spptByYear' => Sppt::selectRaw('tahun, count(*) as total, sum(case when status_bayar = \'lunas\' then 1 else 0 end) as paid, sum(case when status_bayar = \'piutang\' then 1 else 0 end) as unpaid, sum(case when status_bayar = \'piutang\' then pajak_terhutang else 0 end) as total_terhutang')
                ->groupBy('tahun')
                ->orderByDesc('tahun')
                ->get(),
            'latestSubjects' => SubjekPajak::latest()->take(5)->get(),
            'latestPayments' => Pembayaran::with('sppt')->latest('tgl_bayar')->take(5)->get(),
        ]);
    }
}
