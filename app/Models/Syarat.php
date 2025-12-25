<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syarat extends Model
{
    protected $table = 'syarat';

    protected $fillable = [
        'kode',
        'teks',
        'aktif',
        'popup_type',
        'popup_trigger',
        'popup_label',
        'popup_placeholder',
        'popup_options',
    ];

    public function jawaban()
    {
        return $this->hasMany(\App\Models\JawabanSyarat::class, 'syarat_id');
    }
}
