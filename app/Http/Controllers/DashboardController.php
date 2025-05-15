<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return $this->redirectToDashboard($user->peran);
    }

    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return view('dashboard.admin');
            case 'mahasiswa':
                return view('dashboard.mahasiswa');
            case 'dosen':
                return view('dashboard.dosen');
            case 'tendik':
                return view('dashboard.tendik');
            case 'sarpras':
                return view('dashboard.sarpras');
            case 'teknisi':
                return view('dashboard.teknisi');
            default:
                return view('dashboard.default');
        }
    }
}
