<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForwardChainingService;
use App\Models\PenerimaBantuan;
use App\Models\HasilDiagnosa;
use App\Models\Syarat;
use App\Models\JawabanSyarat;

class KelayakanController extends Controller
{
    public function __construct(private ForwardChainingService $fc) {}

    public function index()
    {
       
        if (schema()->hasColumn('penerima_bantuan', 'kelayakan')) {
            $penerima = PenerimaBantuan::select('id', 'nik', 'nama')
                ->whereNull('kelayakan')
                ->orderBy('nama')
                ->get();
        } else {
           
            $sudahDicekIds = HasilDiagnosa::select('penerima_id')
                ->distinct()
                ->pluck('penerima_id');

            $query = PenerimaBantuan::select('id', 'nik', 'nama');

            if ($sudahDicekIds->isNotEmpty()) {
                $query->whereNotIn('id', $sudahDicekIds);
            }

            $penerima = $query->orderBy('nama')->get();
        }

        return view('sp.kelayakan.index', compact('penerima'));
    }

    public function start(Request $r)
    {
        $r->validate([
            'penerima_id' => 'required|exists:penerima_bantuan,id',
        ]);

        $penerima = PenerimaBantuan::select('id','nik','nama')->findOrFail($r->penerima_id);

        $facts = [];
        $next  = $this->fc->nextQuestion($facts);
        if (!$next) {
            $this->fc->saveHistory($penerima->id, 'Tidak Dapat Ditentukan', $facts);
            return view('sp.kelayakan.result', [
                'hasil'    => 'Tidak Dapat Ditentukan',
                'rule'     => null,
                'facts'    => $facts,
                'penerima' => $penerima,
            ]);
        }

        $syarat = Syarat::where('kode', $next)->first();

        return view('sp.kelayakan.ask', [
            'facts'    => $facts,
            'kode'     => $next,
            'teks'     => $this->fc->questionText($next),
            'penerima' => $penerima,
            'syarat'   => $syarat,
        ]);
    }

    public function answer(Request $r)
    {
        $r->validate([
            'facts'       => 'required|string',
            'kode'        => 'required|string',
            'jawaban'     => 'required|in:ya,tidak',
            'penerima_id' => 'required|exists:penerima_bantuan,id',
        ]);

        $penerima = PenerimaBantuan::findOrFail($r->penerima_id);

        
        $facts = json_decode($r->facts, true) ?: [];
        $facts[$r->kode] = $r->jawaban === 'ya';

      
        $syarat = Syarat::where('kode', $r->kode)->first();

        
        $popupValue = null;

        if ($r->has('popup_options')) {
            $opts = array_filter((array) $r->input('popup_options', []));
            $popupValue = $opts ? implode(', ', $opts) : null;
        } else {
            $popupValue = $r->input('popup_value');
            if (is_string($popupValue)) {
                $popupValue = trim($popupValue) === '' ? null : $popupValue;
            }
        }

        if ($syarat) {
            JawabanSyarat::updateOrCreate(
                [
                    'penerima_id' => $penerima->id,
                    'syarat_id'   => $syarat->id,
                ],
                [
                    'kode_gejala' => $syarat->kode,
                    'jawaban'     => $r->jawaban,
                    'popup_value' => $popupValue,
                ]
            );
        }

        
        if ($final = $this->fc->infer($facts)) {
            
            $this->fc->saveHistory($penerima->id, $final['hasil'], $facts);

            if (schema()->hasColumn('penerima_bantuan', 'kelayakan')) {
                $penerima->kelayakan = $final['hasil'];
                $penerima->save();
            }

            return view('sp.kelayakan.result', [
                'hasil'    => $final['hasil'],
                'rule'     => $final['rule'] ?? null,
                'facts'    => $facts,
                'penerima' => $penerima,
            ]);
        }

        $next = $this->fc->nextQuestion($facts);
        if (!$next) {
            
            $this->fc->saveHistory($penerima->id, 'Tidak Layak', $facts);

            if (schema()->hasColumn('penerima_bantuan', 'kelayakan')) {
                $penerima->kelayakan = 'Tidak Layak';
                $penerima->save();
            }

            return view('sp.kelayakan.result', [
                'hasil'    => 'Tidak Layak',
                'rule'     => '(fallback)',
                'facts'    => $facts,
                'penerima' => $penerima,
            ]);
        }

       
        $syaratNext = Syarat::where('kode', $next)->first();

        return view('sp.kelayakan.ask', [
            'facts'    => $facts,
            'kode'     => $next,
            'teks'     => $this->fc->questionText($next),
            'penerima' => $penerima,
            'syarat'   => $syaratNext,
        ]);
    }


   
    public function history(Request $r)
    {
        $penerimaOptions = PenerimaBantuan::select('id','nik','nama')->orderBy('nama')->get();

        $q = HasilDiagnosa::with(['penerima' => function($q) {
            $q->select('id','nik','nama');
        }])->orderByDesc('tanggal')->orderByDesc('id');

        if ($r->filled('penerima_id') && is_numeric($r->penerima_id)) {
            $q->where('penerima_id', $r->penerima_id);
        }

        $items = $q->paginate(20)->withQueryString();

        return view('sp.kelayakan.history', compact('items','penerimaOptions'));
    }
}

if (!function_exists('schema')) {
    function schema() { return \Illuminate\Support\Facades\Schema::getFacadeRoot(); }
}
