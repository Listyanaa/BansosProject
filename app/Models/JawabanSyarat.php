<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSyarat extends Model
{
    protected $table = 'jawaban_syarat';

    protected $fillable = [
        'penerima_id',
        'syarat_id',
        'kode_gejala',
        'jawaban',
        'popup_value',
    ];

    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'penerima_id');
    }

    public function syarat()
    {
        return $this->belongsTo(Syarat::class, 'syarat_id');
    }
}
