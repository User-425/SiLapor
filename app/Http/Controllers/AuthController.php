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
            'nama_pengguna' => 'required',
            'kata_sandi' => 'required',
        ]);

        $pengguna = Pengguna::where('nama_pengguna', $credentials['nama_pengguna'])->first();

        if ($pengguna && Hash::check($credentials['kata_sandi'], $pengguna->kata_sandi)) {
            Auth::login($pengguna);
            return $this->redirectBasedOnRole($pengguna);
        }

        return back()->withErrors([
            'login_error' => 'Nama pengguna atau kata sandi tidak valid.',
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
