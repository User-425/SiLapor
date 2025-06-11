<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nama_pengguna' => 'required|string',
            'kata_sandi' => 'required',
        ]);

        // Coba autentikasi dengan Auth::attempt
        if (Auth::attempt(['nama_pengguna' => $credentials['nama_pengguna'], 'password' => $credentials['kata_sandi']])) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Cek apakah nama pengguna ada
        $pengguna = Pengguna::where('nama_pengguna', $credentials['nama_pengguna'])->first();
        if (!$pengguna) {
            return back()->withErrors([
                'nama_pengguna' => 'Nama pengguna tidak ditemukan.',
            ])->withInput($request->except('kata_sandi'));
        }

        // Kalau nama pengguna ada tapi password salah
        return back()->withErrors([
            'kata_sandi' => 'Kata sandi salah.',
        ])->withInput($request->except('kata_sandi'));
    }

    protected function redirectBasedOnRole($user)
    {
        $intended = session()->pull('url.intended', null);

        if ($intended && $intended !== route('login') && $intended !== url('/')) {
            return redirect($intended);
        }

        return redirect()->route('dashboard', ['role' => $user->peran]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
