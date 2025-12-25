<?php
namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\Syarat;
use Illuminate\Http\Request;

class AturanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));

        $query = Aturan::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kode_aturan', 'like', "%{$search}%")
                  ->orWhere('kondisi', 'like', "%{$search}%")
                  ->orWhere('hasil', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('kode_aturan')->get();

        return view('sp.aturan.index', compact('items', 'search'));
    }

    public function create() { $syarat=Syarat::where('aktif',true)->orderBy('kode')->get(); return view('sp.aturan.create',compact('syarat')); }

    public function store(Request $r) {
        $r->validate([
            'kode_aturan'=>'required|unique:aturan,kode_aturan',
            'hasil'      =>'required|in:Layak,Tidak Layak',
            'kondisi'    =>'required|array|min:1',
        ]);
        Aturan::create([
            'kode_aturan'=>strtoupper(trim($r->kode_aturan)),
            'kondisi'    =>implode(',', array_map('trim',$r->kondisi)),
            'hasil'      =>$r->hasil,
        ]);
        return redirect()->route('sp.aturan.index')->with('success','Aturan dibuat');
    }

    public function edit(Aturan $aturan) {
        $syarat=Syarat::where('aktif',true)->orderBy('kode')->get();
        $selected=$aturan->kondisiArray();
        return view('sp.aturan.edit',compact('aturan','syarat','selected'));
    }

    public function update(Request $r, Aturan $aturan) {
        $r->validate([
            'kode_aturan'=>'required|unique:aturan,kode_aturan,'.$aturan->id,
            'hasil'      =>'required|in:Layak,Tidak Layak',
            'kondisi'    =>'required|array|min:1',
        ]);
        $aturan->update([
            'kode_aturan'=>strtoupper(trim($r->kode_aturan)),
            'kondisi'    =>implode(',', array_map('trim',$r->kondisi)),
            'hasil'      =>$r->hasil,
        ]);
        return redirect()->route('sp.aturan.index')->with('success','Aturan diupdate');
    }

    public function destroy(Aturan $aturan) { $aturan->delete(); return back()->with('success','Aturan dihapus'); }
}
