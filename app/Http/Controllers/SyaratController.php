<?php
namespace App\Http\Controllers;

use App\Models\Syarat;
use Illuminate\Http\Request;

class SyaratController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));

        $query = Syarat::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('teks', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('kode')->get();

        return view('sp.syarat.index', compact('items', 'search'));
    }


    public function create()
    {
        return view('sp.syarat.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'kode'  => 'required|unique:syarat,kode',
            'teks'  => 'required',
            'popup_type'   => 'nullable|in:none,number,text,checkbox,select',
            'popup_trigger'=> 'nullable|in:yes,no,any',
        ]);

        
        $optionsJson = null;
        if ($r->filled('popup_options_raw')) {
            $lines = preg_split("/\r\n|\r|\n/", $r->popup_options_raw);
            $clean = array_values(array_filter(array_map('trim', $lines)));
            if (!empty($clean)) {
                $optionsJson = json_encode($clean);
            }
        }

        Syarat::create([
            'kode'             => strtoupper(trim($r->kode)),
            'teks'             => trim($r->teks),
            'aktif'            => $r->boolean('aktif'),
            'popup_type'       => $r->input('popup_type', 'none'),
            'popup_trigger'    => $r->input('popup_trigger', 'yes'),
            'popup_label'      => $r->input('popup_label'),
            'popup_placeholder'=> $r->input('popup_placeholder'),
            'popup_options'    => $optionsJson,
        ]);

        return redirect()->route('sp.syarat.index')->with('success','Syarat ditambahkan');
    }

    public function edit(Syarat $syarat)
    {
        return view('sp.syarat.edit', ['item' => $syarat]);
    }

    public function update(Request $r, Syarat $syarat)
    {
        $r->validate([
            'kode'  => 'required|unique:syarat,kode,'.$syarat->id,
            'teks'  => 'required',
            'popup_type'   => 'nullable|in:none,number,text,checkbox,select',
            'popup_trigger'=> 'nullable|in:yes,no,any',
        ]);

        $optionsJson = null;
        if ($r->filled('popup_options_raw')) {
            $lines = preg_split("/\r\n|\r|\n/", $r->popup_options_raw);
            $clean = array_values(array_filter(array_map('trim', $lines)));
            if (!empty($clean)) {
                $optionsJson = json_encode($clean);
            }
        }

        $syarat->update([
            'kode'             => strtoupper(trim($r->kode)),
            'teks'             => trim($r->teks),
            'aktif'            => $r->boolean('aktif'),
            'popup_type'       => $r->input('popup_type', 'none'),
            'popup_trigger'    => $r->input('popup_trigger', 'yes'),
            'popup_label'      => $r->input('popup_label'),
            'popup_placeholder'=> $r->input('popup_placeholder'),
            'popup_options'    => $optionsJson,
        ]);

        return redirect()->route('sp.syarat.index')->with('success','Syarat diupdate');
    }

    public function destroy(Syarat $syarat)
    {
        $syarat->delete();
        return back()->with('success','Syarat dihapus');
    }
}
