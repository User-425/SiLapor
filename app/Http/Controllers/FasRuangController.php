<?php

namespace App\Http\Controllers;

use App\Models\FasRuang;
use App\Models\Fasilitas;
use App\Models\Ruang;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\DB;

class FasRuangController extends Controller
{
    public function index()
    {
        $fasRuangs = FasRuang::with(['fasilitas', 'ruang'])->paginate(10);
        return view('pages.fasilitas.index', compact('fasRuangs'));
    }

    public function create()
    {
        $fasilitas = Fasilitas::all();
        $ruangs = Ruang::all();
        return view('pages.fasilitas.create', compact('fasilitas', 'ruangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'kode_fasilitas' => 'required|string|max:50|unique:fasilitas_ruang',
        ]);

        FasRuang::create($validated);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas ruang berhasil ditambahkan.');
    }

    public function edit(FasRuang $fasRuang)
    {
        $fasilitas = Fasilitas::all();
        $ruangs = Ruang::all();
        return view('pages.fasilitas.edit', compact('fasRuang', 'fasilitas', 'ruangs'));
    }

    public function update(Request $request, FasRuang $fasRuang)
    {
        $validated = $request->validate([
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'kode_fasilitas' => 'required|string|max:50|unique:fasilitas_ruang,kode_fasilitas,' . $fasRuang->id_fas_ruang . ',id_fas_ruang',
        ]);

        $fasRuang->update($validated);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas ruang berhasil diperbarui.');
    }

    public function destroy(FasRuang $fasRuang)
    {
        try {
            if ($fasRuang->laporanKerusakan()->count() > 0) {
                return redirect()->route('fasilitas.index')
                    ->with('error', 'Tidak dapat menghapus fasilitas ruang karena masih memiliki laporan kerusakan terkait');
            }

            $fasRuang->delete();
            return redirect()->route('fasilitas.index')
                ->with('success', 'Fasilitas ruang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Gagal menghapus fasilitas ruang: ' . $e->getMessage());
        }
    }

    public function generateQR($id)
    {
        try {
            $fasRuang = FasRuang::with(['fasilitas', 'ruang.gedung'])->findOrFail($id);

            $baseUrl = config('app.url');
            $code = base64_encode($id);
        
            // $development_host = "http://192.168.31.38:8000";
            $url = rtrim($baseUrl, '/') . '/laporan/quick/' . $code;
            // $url = route('laporan.quick', ['code' => base64_encode($id)]);
            $qrcode = QrCode::size(300)
                ->margin(2)
                ->generate($url);

            return view('pages.fasilitas.qr', [ 
                'fasRuang' => $fasRuang,
                'qrcode' => $qrcode,
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Gagal generate QR Code');
        }
    }

    public function history($id)
    {
        $fasRuang = FasRuang::with(['fasilitas', 'ruang.gedung'])->findOrFail($id);
        
        $laporanHistory = LaporanKerusakan::with(['pengguna'])
            ->where('id_fas_ruang', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Calculate statistics
        $totalLaporan = LaporanKerusakan::where('id_fas_ruang', $id)->count();
        
        $completedLaporan = LaporanKerusakan::where('id_fas_ruang', $id)
            ->where('status', 'selesai')
            ->get();
        
        $averageTimeToFix = null;
        if ($completedLaporan->count() > 0) {
            $totalDays = 0;
            foreach ($completedLaporan as $laporan) {
                $created = new \DateTime($laporan->created_at);
                $updated = new \DateTime($laporan->updated_at);
                $diff = $created->diff($updated);
                $totalDays += $diff->days;
            }
            $averageTimeToFix = $completedLaporan->count() > 0 ? 
                round($totalDays / $completedLaporan->count(), 1) : 0;
        }
        
        $currentMonth = date('m');
        $currentYear = date('Y');
        $reportsThisMonth = LaporanKerusakan::where('id_fas_ruang', $id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        
        $statusCounts = LaporanKerusakan::where('id_fas_ruang', $id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Generate trend data for the last 6 months
        $lastSixMonths = [];
        $monthlyReports = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            
            $count = LaporanKerusakan::where('id_fas_ruang', $id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();
            
            $lastSixMonths[] = $monthName;
            $monthlyReports[] = $count;
        }
        
        // Analyze common issues from descriptions
        $commonPhrases = [];
        if ($totalLaporan > 1) {
            $allDescriptions = LaporanKerusakan::where('id_fas_ruang', $id)
                ->pluck('deskripsi')
                ->toArray();
            
            $allText = implode(' ', $allDescriptions);
            $allText = strtolower($allText);
            
            // Remove common words in Indonesian
            $commonWords = ['yang', 'dan', 'di', 'ke', 'dari', 'untuk', 'dengan', 'tidak', 'ini', 'itu', 'adalah', 'pada', 'saya', 'kami', 'kita', 'mereka', 'dia', 'itu', 'juga', 'sudah', 'belum', 'akan', 'bisa', 'harus', 'boleh', 'tidak', 'ada', 'semua', 'beberapa', 'lain', 'lainnya', 'tersebut', 'terkait', 'terutama', 'seperti', 'lebih', 'kurang', 'sangat', 'cukup'];
            foreach ($commonWords as $word) {
                $allText = str_replace(' ' . $word . ' ', ' ', $allText);
            }
            
            // Count word frequency
            $words = explode(' ', $allText);
            $wordCount = array_count_values(array_filter($words));
            arsort($wordCount);
            
            // Get the top 5 common words
            $commonPhrases = array_slice($wordCount, 0, 5, true);
        }
        
        $stats = [
            'total' => $totalLaporan,
            'completed' => $completedLaporan->count(),
            'averageTimeToFix' => $averageTimeToFix,
            'thisMonth' => $reportsThisMonth,
            'statusCounts' => $statusCounts,
            'monthLabels' => $lastSixMonths,
            'monthlyData' => $monthlyReports,
            'commonPhrases' => $commonPhrases
        ];
        
        return view('pages.fasilitas.history', compact('fasRuang', 'laporanHistory', 'stats'));
    }

    public function show($id)
    {
        $fasRuang = FasRuang::with(['fasilitas', 'ruang.gedung'])->findOrFail($id);
        
        return view('pages.fasilitas.show', compact('fasRuang'));
    }

    public function maintenance($id)
    {
        $fasRuang = FasRuang::with(['fasilitas', 'ruang.gedung'])->findOrFail($id);
        
        // Get active reports for this facility
        $activeReports = LaporanKerusakan::where('id_fas_ruang', $id)
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->with('pengguna')
            ->latest()
            ->get();
        
        // Get maintenance history
        $completedReports = LaporanKerusakan::where('id_fas_ruang', $id)
            ->where('status', 'selesai')
            ->with('pengguna')
            ->latest()
            ->take(5)
            ->get();
        
        return view('pages.fasilitas.maintenance', compact('fasRuang', 'activeReports', 'completedReports'));
    }
}
