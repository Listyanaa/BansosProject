<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use Carbon\Carbon;
use App\Models\Syarat;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kecamatan     = $request->input('kecamatan');
        $bulan         = $request->input('bulan'); 
        $jenis_kelamin = $request->input('jenis_kelamin');
        $kelayakan     = $request->input('kelayakan');

        
        $syaratPopup = Syarat::where('popup_type', '!=', 'none')
            ->orderBy('kode')
            ->get();

        $query = PenerimaBantuan::query()
            ->select('penerima_bantuan.*');

        foreach ($syaratPopup as $s) {
           
            $alias = 'sp_' . strtolower($s->kode);

            $sub = DB::table('jawaban_syarat')
                ->select('popup_value')
                ->whereColumn('jawaban_syarat.penerima_id', 'penerima_bantuan.id')
                ->where('jawaban_syarat.kode_gejala', $s->kode)
                ->limit(1);

            $query->addSelect([
                $alias => $sub,
            ]);
        }

        if ($kecamatan) {
            $query->where('kecamatan', $kecamatan);
        }

        if ($bulan) {
            $query->whereMonth('tanggal_menerima_layanan', Carbon::parse($bulan)->month)
                ->whereYear('tanggal_menerima_layanan', Carbon::parse($bulan)->year);
        }

        if ($jenis_kelamin) {
            $query->where('jenis_kelamin', $jenis_kelamin);
        }

        if ($kelayakan) {
            $query->where('kelayakan', $kelayakan);
        }

        $data = $query->get();

       
        $listKecamatan    = PenerimaBantuan::select('kecamatan')->distinct()->pluck('kecamatan');
        $listJenisKelamin = PenerimaBantuan::select('jenis_kelamin')->distinct()->pluck('jenis_kelamin');
        $listKelayakan    = ['Layak', 'Tidak Layak'];

        $chartQuery = PenerimaBantuan::query();
        if ($kecamatan)     $chartQuery->where('kecamatan', $kecamatan);
        if ($bulan)         $chartQuery->whereMonth('tanggal_menerima_layanan', Carbon::parse($bulan)->month)
                                    ->whereYear('tanggal_menerima_layanan', Carbon::parse($bulan)->year);
        if ($jenis_kelamin) $chartQuery->where('jenis_kelamin', $jenis_kelamin);
        if ($kelayakan)     $chartQuery->where('kelayakan', $kelayakan);

        $chartData = $chartQuery
            ->select('kecamatan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan')
            ->get()
            ->pluck('total','kecamatan');

        $pieQuery = PenerimaBantuan::query();
        if ($kecamatan) $pieQuery->where('kecamatan', $kecamatan);
        if ($bulan)     $pieQuery->whereMonth('tanggal_menerima_layanan', Carbon::parse($bulan)->month)
                                ->whereYear('tanggal_menerima_layanan', Carbon::parse($bulan)->year);
        if ($kelayakan) $pieQuery->where('kelayakan', $kelayakan);

        $pieData = $pieQuery->select('jenis_kelamin')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->get()
            ->pluck('total','jenis_kelamin');

        $lineQuery = PenerimaBantuan::query();
        if ($kecamatan)     $lineQuery->where('kecamatan', $kecamatan);
        if ($jenis_kelamin) $lineQuery->where('jenis_kelamin', $jenis_kelamin);
        if ($kelayakan)     $lineQuery->where('kelayakan', $kelayakan);

        $lineData = $lineQuery
            ->selectRaw("DATE_FORMAT(tanggal_menerima_layanan, '%Y-%m') as bulan")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total','bulan');

        $kelayakanData = PenerimaBantuan::select('kelayakan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kelayakan')
            ->get()
            ->pluck('total','kelayakan');

        return view('laporan.index', compact(
            'data',
            'listKecamatan',
            'listJenisKelamin',
            'listKelayakan',
            'bulan',
            'chartData',
            'pieData',
            'lineData',
            'kelayakanData',
            'syaratPopup'   
        ));
    }

}
