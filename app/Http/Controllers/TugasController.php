<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\LaporanKerusakan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('peran:sarpras')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Show the list of tasks that need to be assigned
     */
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');
        
        // Get active batches with reports that need assignment
        $batches = Batch::where('status', 'aktif')
            ->with(['laporans' => function($query) {
                $query->where('status', 'diproses')
                    ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'kriteria']);
            }])
            ->get();

        // Count statistics
        $totalBelumDitugaskan = 0;
        foreach ($batches as $batch) {
            $totalBelumDitugaskan += $batch->laporans->count();
        }
        
        $batchesAktif = Batch::where('status', 'aktif')->count();
        $dalamPengerjaan = Tugas::where('status', '!=', 'selesai')->count();

        return view('pages.tugas.index', compact('batches', 'totalBelumDitugaskan', 'batchesAktif', 'dalamPengerjaan', 'role'));
    }

    /**
     * Show form to create a new task assignment
     */
    public function create(LaporanKerusakan $laporan)
    {
        // Check if laporan belongs to an active batch
        if (!$laporan->id_batch || $laporan->batch->status !== 'aktif') {
            return redirect()->route('tugas.index')
                ->with('error', 'Hanya laporan dalam batch aktif yang dapat ditugaskan.');
        }

        // Check if status is appropriate for assignment
        if ($laporan->status !== 'diproses') {
            return redirect()->route('tugas.index')
                ->with('error', 'Hanya laporan dengan status "diproses" yang dapat ditugaskan.');
        }
        
        // Load the laporan with its relationships
        $laporan->load(['pengguna', 'fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'kriteria', 'batch']);
        
        // Get available technicians
        $teknisi = User::where('peran', 'teknisi')->get();
        
        return view('pages.tugas.create', compact('laporan', 'teknisi'));
    }

    /**
     * Store a new task assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required|exists:laporan_kerusakan,id_laporan',
            'id_pengguna' => 'required|exists:users,id_pengguna',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'batas_waktu' => 'nullable|date|after:now',
            'catatan' => 'nullable|string',
        ]);
        
        $laporan = LaporanKerusakan::findOrFail($request->id_laporan);
        
        // Verify the laporan is in an active batch
        if (!$laporan->id_batch || $laporan->batch->status !== 'aktif') {
            return redirect()->route('tugas.index')
                ->with('error', 'Hanya laporan dalam batch aktif yang dapat ditugaskan.');
        }
        
        // Check if status is appropriate for assignment
        if ($laporan->status !== 'diproses') {
            return redirect()->route('tugas.index')
                ->with('error', 'Hanya laporan dengan status "diproses" yang dapat ditugaskan.');
        }

        DB::beginTransaction();
        try {
            // Create task assignment
            $tugas = Tugas::create([
                'id_laporan' => $request->id_laporan,
                'id_teknisi' => $request->id_pengguna,
                'id_sarpras' => Auth::id(),
                'tanggal_mulai' => now(),
                'batas_waktu' => $request->batas_waktu,
                'prioritas' => $request->prioritas,
                'catatan' => $request->catatan,
                'status' => 'ditugaskan',
            ]);
            
            // Update laporan status
            $laporan->status = 'ditugaskan';
            $laporan->save();
            
            DB::commit();
            
            return redirect()->route('tugas.index')
                ->with('success', 'Penugasan berhasil dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat penugasan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Other methods...

    /**
     * API endpoint to get laporan details
     */
    public function getLaporanDetails($id)
    {
        $laporan = LaporanKerusakan::with([
            'fasilitasRuang.fasilitas', 
            'fasilitasRuang.ruang.gedung',
            'kriteria',
            'batch'
        ])->findOrFail($id);
        
        return response()->json($laporan);
    }
}
