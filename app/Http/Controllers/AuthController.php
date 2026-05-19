<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // =========================
    // HALAMAN LOGIN
    // =========================
    public function showLogin()
    {
        return view('login');
    }

    // =========================
    // PROSES LOGIN
    // =========================
    public function login(Request $request)
    {

        // VALIDASI
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials))
        {

            $request->session()->regenerate();

            $user = Auth::user();

            // =========================
            // CEK STATUS (FIX BIAR TIDAK ERROR)
            // =========================
            if(strtolower($user->status ?? '') != 'aktif'){
                Auth::logout();
                return back()->with('error','User tidak aktif');
            }

            // =========================
            // NORMALISASI ROLE (FIX BIAR AMAN)
            // =========================
            $role = strtolower(trim($user->role ?? ''));

            // =========================
            // REDIRECT SESUAI ROLE
            // =========================
            if($role == 'admin'){
                return redirect('/admin/dashboard');
            }

            if($role == 'pekerja'){
                return redirect('/pekerja/dashboard');
            }

            if($role == 'supervisor'){
                return redirect('/supervisor/dashboard');
            }

            // HANDLE BANYAK VARIASI SAFETY
            if($role == 'safety' || $role == 'safety officer'){
                return redirect('/safety-officer/dashboard');
            }

            // =========================
            // ROLE TIDAK DIKENALI
            // =========================
            Auth::logout();
            return back()->with('error','Role tidak dikenali');

        }

        // =========================
        // LOGIN GAGAL
        // =========================
        return back()->with('error','Email atau Password Salah');

    }

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');

    }

}