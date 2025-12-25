<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    protected $table = 'aturan';
    protected $fillable = ['kode_aturan','kondisi','hasil'];

    public function kondisiArray(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string)$this->kondisi))));
    }
}
