<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaBantuan extends Model
{
    use HasFactory;

    protected $table = 'penerima_bantuan';

    protected $fillable = [
        'nik',
        'nama',
        'tempat_tgl_lahir',
        'jenis_kelamin',
        'agama',   
        'status_pernikahan',
        'status_kepala_keluarga',
        'kecamatan',
        'kelurahan',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'pekerjaan',
        'status_pekerjaan',
        'penghasilan_bulanan',
        'pengeluaran_bulanan',
        'jumlah_tanggungan',
        'status_dtks',
        'status_kerentanan',
        'kepemilikan_rumah',
        'kondisi_rumah',
        'daya_listrik',
        'sumber_air',
        'kepemilikan_kendaraan',
        'jenis_kendaraan',
        'tanggal_menerima_layanan',
        'tanggal_meninggal',
        'kelayakan',
        'foto_bukti'
    ];

    public function hasilDiagnosa()
    {
        return $this->hasMany(\App\Models\HasilDiagnosa::class, 'penerima_id');
    }

    public function jawabanSyarat()
    {
        return $this->hasMany(\App\Models\JawabanSyarat::class, 'penerima_id');
    }
}
