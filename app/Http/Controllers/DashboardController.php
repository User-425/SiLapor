<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanKerusakan;
use Carbon\Carbon;

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
                return $this->adminDashboard();
            case 'mahasiswa':
                return $this->mahasiswaDashboard();
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

    public function showByRole($role)
    {
        return $this->redirectToDashboard($role);
    }
       public function adminDashboard()
    {
        $stats = [
            'users' => \App\Models\Pengguna::count(),
            'facilities' => \App\Models\Fasilitas::count(),
            'buildings' => \App\Models\Gedung::count(),
            'rooms' => \App\Models\Ruang::count(),
        ];
        return view('pages.dashboard.admin', compact('stats'));
    }

    public function mahasiswaDashboard()
    {
        $userId = Auth::id();

        $totalLaporan = LaporanKerusakan::where('id_pengguna', $userId)->count();
        $prosesLaporan = LaporanKerusakan::where('id_pengguna', $userId)
            ->where('status', 'proses')
            ->count();
        $selesaiLaporan = LaporanKerusakan::where('id_pengguna', $userId)
            ->where('status', 'selesai')
            ->count();

        $recentReports = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang'])
            ->where('id_pengguna', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.dashboard.mahasiswa', compact(
            'totalLaporan',
            'prosesLaporan',
            'selesaiLaporan',
            'recentReports'
        ));
    }
}
