<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenerimaBantuanController extends Controller
{
    
    public function create()
    {
        return view('tambahdata.index');
    }

    public function destroy($id)
    {
        $data = PenerimaBantuan::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data penerima bantuan berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $data = PenerimaBantuan::findOrFail($id);

        $rules = [
            'nik'       => 'required|unique:penerima_bantuan,nik,' . $data->id,
            'nama'      => 'required|string|max:255',
            'latitude'  => 'required|numeric|between:-90,90|lt:0',
            'longitude' => 'required|numeric|between:-180,180',
        ];

        $messages = [
            'nik.required'       => 'NIK wajib diisi.',
            'nik.unique'         => 'NIK sudah digunakan oleh data lain.',
            'nama.required'      => 'Nama wajib diisi.',

            'latitude.required'  => 'Latitude wajib diisi.',
            'latitude.numeric'   => 'Latitude harus berupa angka.',
            'latitude.between'   => 'Nilai latitude harus di antara -90 dan 90.',
            'latitude.lt'        => 'Latitude harus bernilai negatif (wajib pakai tanda -).',

            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric'  => 'Longitude harus berupa angka.',
            'longitude.between'  => 'Nilai longitude harus di antara -180 dan 180.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->route('penerima.index')   
                ->withErrors($validator)
                ->withInput()
                ->with('edit_id', $data->id);
        }

        
        $validated = $validator->validated();

        
        $data->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_tgl_lahir' => $request->tempat_tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'            => $request->agama,
            'status_pernikahan' => $request->status_pernikahan,
            'status_kepala_keluarga' => $request->status_kepala_keluarga,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'alamat_lengkap' => $request->alamat_lengkap,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'pekerjaan' => $request->pekerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'penghasilan_bulanan' => $request->penghasilan_bulanan,
            'pengeluaran_bulanan' => $request->pengeluaran_bulanan,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'status_dtks' => $request->status_dtks,
            'status_kerentanan' => is_array($request->status_kerentanan)
                ? implode(',', $request->status_kerentanan)
                : $request->status_kerentanan,
            'kepemilikan_rumah' => $request->kepemilikan_rumah,
            'kondisi_rumah' => $request->kondisi_rumah,
            'daya_listrik' => $request->daya_listrik,
            'sumber_air' => $request->sumber_air,
            'kepemilikan_kendaraan' => $request->kepemilikan_kendaraan,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tanggal_menerima_layanan' => $request->tanggal_menerima_layanan,
            'tanggal_meninggal' => $request->tanggal_meninggal,
           
        ]);

        return redirect()
            ->route('penerima.index')
            ->with('success', 'Data penerima bantuan berhasil diperbarui.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nik'       => 'required|unique:penerima_bantuan,nik',
            'nama'      => 'required|string|max:255',
            'latitude'  => 'required|numeric|between:-90,90|lt:0',
            'longitude' => 'required|numeric|between:-180,180',
        ], [
            'nik.unique'        => 'NIK sudah digunakan sebelumnya.',
            'nama.required'     => 'Nama wajib diisi.',

            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric'  => 'Latitude harus berupa angka.',
            'latitude.between'  => 'Nilai latitude harus di antara -90 dan 90.',
            'latitude.lt'       => 'Latitude harus bernilai negatif (wajib pakai tanda -).',

            'longitude.required'=> 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Nilai longitude harus di antara -180 dan 180.',
        ]);

       
        $penerima = PenerimaBantuan::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tempat_tgl_lahir' => $request->tempat_tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'            => $request->agama,
            'status_pernikahan' => $request->status_pernikahan,
            'status_kepala_keluarga' => $request->status_kepala_keluarga,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'alamat_lengkap' => $request->alamat_lengkap,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'pekerjaan' => $request->pekerjaan,
            'status_pekerjaan' => $request->status_pekerjaan,
            'penghasilan_bulanan' => $request->penghasilan_bulanan,
            'pengeluaran_bulanan' => $request->pengeluaran_bulanan,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'status_dtks' => $request->status_dtks,
            'status_kerentanan' => is_array($request->status_kerentanan)
                ? implode(',', $request->status_kerentanan)
                : $request->status_kerentanan,
            'kepemilikan_rumah' => $request->kepemilikan_rumah,
            'kondisi_rumah' => $request->kondisi_rumah,
            'daya_listrik' => $request->daya_listrik,
            'sumber_air' => $request->sumber_air,
            'kepemilikan_kendaraan' => $request->kepemilikan_kendaraan,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tanggal_menerima_layanan' => $request->tanggal_menerima_layanan,
            'tanggal_meninggal' => $request->tanggal_meninggal,
            
        ]);

        return redirect()->route('penerima.index')->with('success', 'Data berhasil ditambahkan!');
    }

    
    public function index(Request $request)
    {
        $query = PenerimaBantuan::query();

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('nik', 'like', "%{$keyword}%")
                    ->orWhere('kecamatan', 'like', "%{$keyword}%")
                    ->orWhere('kelurahan', 'like', "%{$keyword}%")
                    ->orWhere('alamat_lengkap', 'like', "%{$keyword}%");
            });
        }

        $penerima = $query->get();

        $editItem = null;
        if (session('edit_id')) {
            $editItem = PenerimaBantuan::find(session('edit_id'));
        }

        return view('databansos.index', compact('penerima', 'editItem'));
    }
}
