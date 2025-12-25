<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilDiagnosa extends Model
{
    protected $table = 'hasil_diagnosa';
    protected $fillable = ['penerima_id','hasil','tanggal','jejak'];
    protected $casts = ['tanggal'=>'datetime','jejak'=>'array'];

    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'penerima_id');
    }
}
