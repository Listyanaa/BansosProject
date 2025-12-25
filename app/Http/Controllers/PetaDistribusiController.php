<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Syarat;
use Illuminate\Support\Facades\DB;

class PetaDistribusiController extends Controller
{
    public function index()
    {
       
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

            $query->addSelect([$alias => $sub]);
        }

       
        $data = $query
            ->whereRaw('LOWER(kelayakan) <> ?', ['tidak layak'])
            ->get();
        
        foreach ($data as $row) {
            $row->foto_bukti = $this->parseFotoField($row->foto_bukti);
        }

       
        $totalTitik = $data->filter(function ($r) {
            return !empty($r->latitude) && !empty($r->longitude);
        })->count();

        $totalLayak = $data->count();

        return view('peta-distribusi', compact('data', 'syaratPopup', 'totalTitik', 'totalLayak'));    

        
        foreach ($data as $row) {
            $row->foto_bukti = $this->parseFotoField($row->foto_bukti);
        }

        
        return view('peta-distribusi', compact('data', 'syaratPopup'));
    }


    public function search(Request $request)
    {
        
        $keyword = trim((string) $request->get('nik', ''));

        
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

            $query->addSelect([$alias => $sub]);
        }


        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nik', 'like', "%{$keyword}%")
                ->orWhere('nama', 'like', "%{$keyword}%");
            });
        }

       
        $query->whereRaw('LOWER(kelayakan) <> ?', ['tidak layak']);

        $data = $query->get();

        foreach ($data as $row) {
            $row->foto_bukti = $this->parseFotoField($row->foto_bukti);
        }

        return response()->json($data);
    }


    
    public function uploadFoto(Request $request, $nik)
    {
        $request->validate([
            'foto_bukti'   => 'required',
            'foto_bukti.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = PenerimaBantuan::where('nik', $nik)->firstOrFail();

        $existing = $this->parseFotoField($data->foto_bukti);
        $index    = $request->input('index', null);

        $files = $request->file('foto_bukti');
        if (!is_array($files)) {
            $files = [$files];
        }

        if ($index !== null) {
           
            $i = (int) $index;

            if (isset($existing[$i]) && $existing[$i]) {
                if (Storage::disk('public')->exists($existing[$i])) {
                    Storage::disk('public')->delete($existing[$i]);
                }
            }

            $path = $files[0]->store('foto-distribusi', 'public');
            $existing[$i] = $path;
        } else {
            
            foreach ($files as $file) {
                $existing[] = $file->store('foto-distribusi', 'public');
            }
        }

        $existing = array_values($existing);
        $data->foto_bukti = $existing ? json_encode($existing) : null;
        $data->save();

        return response()->json([
            'status'     => 'success',
            'message'    => 'Foto berhasil disimpan.',
            'foto_bukti' => $existing,
        ]);
    }

    
    public function hapusFoto(Request $request, $nik)
    {
        try {
            if (!$request->has('index')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Index foto tidak dikirim.',
                ], 422);
            }

            $idx = (int) $request->input('index');

           
            $data = PenerimaBantuan::where('nik', $nik)->first();
            if (!$data) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data penerima tidak ditemukan.',
                ], 404);
            }

            $existing = $this->parseFotoField($data->foto_bukti);

            if (!isset($existing[$idx])) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Foto tidak ditemukan pada index ini.',
                ], 404);
            }

            $oldPath = $existing[$idx];

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

           
            array_splice($existing, $idx, 1);
            $existing = array_values($existing);

            $data->foto_bukti = $existing ? json_encode($existing) : null;
            $data->save();

            return response()->json([
                'status'     => 'success',
                'message'    => 'Foto berhasil dihapus.',
                'foto_bukti' => $existing,
            ]);
        } catch (\Throwable $e) {
          
            Log::error('Gagal hapus foto distribusi', [
                'nik'    => $nik,
                'index'  => $request->input('index'),
                'error'  => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan di server saat menghapus foto.',
            ], 500);
        }
    }

    
    private function parseFotoField($value)
    {
        if (!$value) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return [$value];
    }
}
