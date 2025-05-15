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
                return view('pages.dashboard.admin');
            case 'mahasiswa':
                return view('pages.dashboard.mahasiswa');
            case 'dosen':
                return view('pages.dashboard.dosen');
            case 'tendik':
                return view('pages.dashboard.tendik');
            case 'sarpras':
                return view('pages.dashboard.sarpras');
            case 'teknisi':
                return view('pages.dashboard.teknisi');
            default:
                return view('pages.dashboard.default');
        }
    }
}
