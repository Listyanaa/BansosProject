<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
       
        $total      = PenerimaBantuan::count();
        $layak      = PenerimaBantuan::where('kelayakan', 'Layak')->count();
        $tidakLayak = PenerimaBantuan::where('kelayakan', 'Tidak Layak')->count();

        $count = [
            'total'        => $total,
            'kedaruratan'  => $layak, 

            'layak'        => $layak,
            'tidak_layak'  => $tidakLayak,
            'persen_layak' => $total > 0 ? round(($layak / $total) * 100, 1) : 0,
        ];

        $chartData = PenerimaBantuan::select('kecamatan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan')
            ->pluck('total', 'kecamatan');

        $pieData = PenerimaBantuan::select('jenis_kelamin')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $lineData = PenerimaBantuan::selectRaw("DATE_FORMAT(tanggal_menerima_layanan, '%Y-%m') as bulan")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $kelayakanData = PenerimaBantuan::select('kelayakan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kelayakan')
            ->pluck('total', 'kelayakan');

        return view('dashboard.admin', compact('count', 'chartData', 'pieData', 'lineData', 'kelayakanData'));
    }

    public function staf()
    {
        $total      = PenerimaBantuan::count();
        $layak      = PenerimaBantuan::where('kelayakan', 'Layak')->count();
        $tidakLayak = PenerimaBantuan::where('kelayakan', 'Tidak Layak')->count();

        $count = [
            'total'        => $total,
            'kedaruratan'  => $layak,
            'layak'        => $layak,
            'tidak_layak'  => $tidakLayak,
            'persen_layak' => $total > 0 ? round(($layak / $total) * 100, 1) : 0,
        ];

        $chartData = PenerimaBantuan::select('kecamatan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan')
            ->pluck('total', 'kecamatan');

        $pieData = PenerimaBantuan::select('jenis_kelamin')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $lineData = PenerimaBantuan::selectRaw("DATE_FORMAT(tanggal_menerima_layanan, '%Y-%m') as bulan")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $belumDiperiksa = max($total - $layak - $tidakLayak, 0);

        $kelayakanData = collect([
            'Layak'           => $layak,
            'Tidak Layak'     => $tidakLayak,
            'Belum Diperiksa' => $belumDiperiksa,
        ]);

        return view('dashboard.staf', compact('count', 'chartData', 'pieData', 'lineData', 'kelayakanData'));
    }

    public function pimpinan()
    {
        $total      = PenerimaBantuan::count();
        $layak      = PenerimaBantuan::where('kelayakan', 'Layak')->count();
        $tidakLayak = PenerimaBantuan::where('kelayakan', 'Tidak Layak')->count();

        $count = [
            'total'        => $total,
            'kedaruratan'  => $layak,
            'layak'        => $layak,
            'tidak_layak'  => $tidakLayak,
            'persen_layak' => $total > 0 ? round(($layak / $total) * 100, 1) : 0,
        ];

        $chartData = PenerimaBantuan::select('kecamatan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan')
            ->pluck('total', 'kecamatan');

        $pieData = PenerimaBantuan::select('jenis_kelamin')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $lineData = PenerimaBantuan::selectRaw("DATE_FORMAT(tanggal_menerima_layanan, '%Y-%m') as bulan")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $kelayakanData = PenerimaBantuan::select('kelayakan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kelayakan')
            ->pluck('total', 'kelayakan');

        return view('dashboard.pimpinan', compact('count', 'chartData', 'pieData', 'lineData', 'kelayakanData'));
    }
}
