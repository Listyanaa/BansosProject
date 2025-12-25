<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();                  
            if (!$user) {                           
                return back()->withErrors(['email' => 'Login gagal: sesi belum terset.']);
            }
           
            $request->session()->put('nama', $user->nama);
            $request->session()->put('role', $user->role);

            /** @var \App\Models\User $user */      // <-- hint buat IDE
            $role = $user->role;
            
            return match ($role) {
                'Admin'    => redirect()->route('dashboard.admin'),
                'Staf'     => redirect()->route('dashboard.staf'),
                'Pimpinan' => redirect()->route('dashboard.pimpinan'),
                default    => abort(403, 'Role tidak dikenali'),
            };
        }


        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

  
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget(['nama', 'role']); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
