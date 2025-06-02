<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanKerusakan;
use App\Models\Tugas;
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
                return $this->dosenDashboard();
            case 'tendik':
                return view('pages.dashboard.tendik');
            case 'sarpras':
                return $this->sarprasDashboard();
            case 'teknisi':
                return $this->teknisiDashboard();
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
    public function dosenDashboard()
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

        return view('pages.dashboard.dosen', compact(
            'totalLaporan',
            'prosesLaporan',
            'selesaiLaporan',
            'recentReports'
        ));
    }

    public function teknisiDashboard()
{
    $teknisiId = Auth::user()->id_pengguna;

    $ditugaskan = Tugas::where('id_pengguna', $teknisiId)->where('status', 'ditugaskan')->count();
    $dikerjakan = Tugas::where('id_pengguna', $teknisiId)->where('status', 'dikerjakan')->count();
    $selesai = Tugas::where('id_pengguna', $teknisiId)->where('status', 'selesai')->count();

    $tugasAktif = Tugas::with('laporan.fasilitasRuang.fasilitas', 'laporan.fasilitasRuang.ruang')
        ->where('id_pengguna', $teknisiId)
        ->whereIn('status', ['ditugaskan', 'dikerjakan'])
        ->latest()
        ->take(5)
        ->get();

    return view('pages.dashboard.teknisi', compact('ditugaskan', 'dikerjakan', 'selesai', 'tugasAktif'));
}
public function sarprasDashboard()
{
    $laporanStats = [
        'menunggu_verifikasi' => LaporanKerusakan::where('status', 'menunggu_verifikasi')->count(),
        'diproses' => LaporanKerusakan::where('status', 'diproses')->count(),
        'diperbaiki' => LaporanKerusakan::where('status', 'diperbaiki')->count(),
        'selesai' => LaporanKerusakan::where('status', 'selesai')->count(),
        'ditolak' => LaporanKerusakan::where('status', 'ditolak')->count(),
        'total' => LaporanKerusakan::count()
    ];
    
    $pendingReports = LaporanKerusakan::where('status', 'menunggu_verifikasi')
        ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna'])
        ->latest()
        ->take(5)
        ->get();
    
    $recentTasks = Tugas::with(['laporan.fasilitasRuang.fasilitas', 'laporan.fasilitasRuang.ruang', 'teknisi'])
        ->latest()
        ->take(5)
        ->get();
    
    $reportsByLocation = DB::table('laporan_kerusakan')
        ->join('fasilitas_ruang', 'laporan_kerusakan.id_fas_ruang', '=', 'fasilitas_ruang.id_fas_ruang')
        ->join('ruang', 'fasilitas_ruang.id_ruang', '=', 'ruang.id_ruang')
        ->join('gedung', 'ruang.id_gedung', '=', 'gedung.id_gedung')
        ->select('gedung.nama_gedung', DB::raw('count(*) as total'))
        ->groupBy('gedung.id_gedung', 'gedung.nama_gedung')
        ->orderBy('total', 'desc')
        ->take(5)
        ->get();
    
    $startDate = Carbon::now()->subDays(6)->startOfDay();
    $endDate = Carbon::now()->endOfDay();
    
    $reportTrend = LaporanKerusakan::whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    $dateRange = [];
    for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
        $formattedDate = $date->format('Y-m-d');
        $count = 0;
        
        foreach ($reportTrend as $trend) {
            if ($trend->date === $formattedDate) {
                $count = $trend->count;
                break;
            }
        }
        
        $dateRange[] = [
            'date' => $formattedDate,
            'day' => $date->format('D'),
            'count' => $count
        ];
    }
    
    $currentMonth = Carbon::now()->startOfMonth();
    $monthlyStats = [
        'total' => LaporanKerusakan::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count(),
        'selesai' => LaporanKerusakan::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->where('status', 'selesai')
            ->count(),
    ];
    
    $priorityReports = LaporanKerusakan::where('ranking', '>=', 4)
        ->where('status', '!=', 'selesai')
        ->where('status', '!=', 'ditolak')
        ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna'])
        ->latest()
        ->take(3)
        ->get();
    
    return view('pages.dashboard.sarpras', compact(
        'laporanStats',
        'pendingReports',
        'recentTasks',
        'reportsByLocation',
        'dateRange',
        'monthlyStats',
        'priorityReports'
    ));
}
}
