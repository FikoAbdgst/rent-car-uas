<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('home');
            } elseif ($user->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            }

            return redirect()->route('home');
        }

        return view('login.index');
    }

    public function proses(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email salah',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('home')->with('success', 'Selamat datang Admin!');
            } elseif ($user->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard')->with('success', 'Selamat datang Pemilik!');
            } else {
                return redirect()->route('home') . with('success', 'Selamat datang!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    public function keluar(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout');
    }
}
