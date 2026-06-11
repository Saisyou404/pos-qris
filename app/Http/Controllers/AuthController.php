<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->has('user_id')) {
            $role = session('user_role');
            return redirect($role === 'admin' ? '/admin/dashboard' : '/kasir/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required'
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if ($user && Hash::check($request->kata_sandi, $user->kata_sandi)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            // Login berhasil - simpan data user ke session
            session([
                'user_id' => $user->id,
                'user_role' => $user->peran,
                'user_name' => $user->nama,
            ]);
            
            // Redirect berdasarkan role
            if ($user->peran === 'admin') {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/kasir/dashboard');
            }
        }

        return back()->with('error', 'Email atau kata sandi salah.');
    }

    public function logout(Request $request)
    {
        // Hapus semua session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}