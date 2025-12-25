<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ManajemenPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%"); 
            });
        })->get();

        return view('manajemen.index', compact('users'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'role'   => 'required|in:Admin,Staf,Pimpinan',
        ], [
            'nama.required'   => 'Nama wajib diisi.',
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.unique'    => 'Email sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same'     => 'Konfirmasi password tidak cocok.',

            'role.required'  => 'Role wajib dipilih.',
            'role.in'        => 'Role tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'store')
                ->withInput()
                ->with('modal', 'add'); 
        }

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('manajemen.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('manajemen.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('123456'); 
        $user->save();

        return redirect()->route('manajemen.index')->with('success', 'Password berhasil direset ke 123456');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:Admin,Staf,Pimpinan',
        ], [
            'nama.required'   => 'Nama wajib diisi.',
            'email.required'  => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'email.unique'    => 'Email sudah digunakan.',
            'role.required'   => 'Role wajib dipilih.',
            'role.in'         => 'Role tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'update')
                ->withInput()
                ->with([
                    'modal' => 'edit',
                    'edit_id' => $id, 
                ]);
        }

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('manajemen.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

}
