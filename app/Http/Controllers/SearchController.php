<?php
namespace App\Http\Controllers;

use App\Models\FasRuang;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\LaporanKerusakan;
use App\Models\Ruang;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $user = Auth::user();
        
        if (empty($query)) {
            return view('search.results', [
                'query' => '',
                'results' => [],
                'totalResults' => 0
            ]);
        }

        // Initialize results array
        $results = [
            'fasilitas' => [],
            'ruang' => [],
            'gedung' => [],
            'laporan' => [],
            'tugas' => [],
        ];
        
        // Search in fasilitas (facilities)
        $fasilitasResults = Fasilitas::where('nama_fasilitas', 'like', "%{$query}%")    
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        $results['fasilitas'] = $fasilitasResults;
        
        // Search in unit fasilitas (facility units)
        $fasRuangResults = FasRuang::where('kode_fasilitas', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        $results['fas_ruang'] = $fasRuangResults;
        
        // Search in ruang (rooms)
        $ruangResults = Ruang::where('nama_ruang', 'like', "%{$query}%")
            ->orWhere('deskripsi_lokasi', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        $results['ruang'] = $ruangResults;
        
        // Search in gedung (buildings)
        $gedungResults = Gedung::where('nama_gedung', 'like', "%{$query}%")
            ->orWhere('deskripsi_lokasi', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        $results['gedung'] = $gedungResults;
        
        // Search in laporan (reports) - with permission filtering
        $laporanQuery = LaporanKerusakan::where(function($q) use ($query) {
                $q->where('deskripsi', 'like', "%{$query}%");
            });
            
        // Apply permission filtering
        if ($user->peran === 'mahasiswa' || $user->peran === 'dosen' || $user->peran === 'tendik') {
            // Regular users can only see their own reports
            $laporanQuery->where('id_pelapor', $user->id);
        }
        // Admin, sarpras and teknisi can see all reports
        
        $results['laporan'] = $laporanQuery->limit(10)->get();
        
        // Search in tugas (tasks) - with permission filtering
        if ($user->peran === 'sarpras' || $user->peran === 'teknisi') {
            $tugasQuery = Tugas::where(function($q) use ($query) {
                $q->where('catatan', 'like', "%{$query}%")
                  ->orWhere('prioritas', 'like', "%{$query}%");
            });
            
            // Teknisi can only see tasks assigned to them
            if ($user->peran === 'teknisi') {
                $tugasQuery->where('id_teknisi', $user->id);
            }
            
            $results['tugas'] = $tugasQuery->limit(5)->get();
        }
        
        // Calculate total results
        $totalResults = 
            $results['fasilitas']->count() +
            $results['fas_ruang']->count() +
            $results['ruang']->count() +
            $results['gedung']->count() +
            $results['laporan']->count() +
            (isset($results['tugas']) ? count($results['tugas']) : 0);
        
        return view('pages.search.results', [
            'query' => $query,
            'results' => $results,
            'totalResults' => $totalResults
        ]);
    }
}