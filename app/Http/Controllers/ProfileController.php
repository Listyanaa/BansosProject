<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6',
            'password_konfirmasi' => 'required|same:password_baru',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_konfirmasi.required'  => 'Konfirmasi password wajib diisi.',
            'password_baru.min' => 'Password baru minimal 6 karakter.',
            'password_konfirmasi.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }


        $user = Auth::user();
        if (!$user instanceof User) {
            return back()->withErrors(['msg' => 'Gagal mengambil data pengguna. Pastikan Anda login ulang.']);
        }


        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
