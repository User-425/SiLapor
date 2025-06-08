<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\LaporanKerusakan;
use App\Models\Pengguna;
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
            ->with(['laporans' => function ($query) {
                $query->where('status', 'diproses')
                    ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'kriteria']);
            }])
            ->get();

        // Count statistics 
        $totalBelumDitugaskan = LaporanKerusakan::whereHas('batch', function($query) {
            $query->where('status', 'aktif');
        })->where('status', 'diproses')
          ->whereNotIn('id_laporan', function($query) {
              $query->select('id_laporan')->from('tugas');
          })
          ->count();
        
        $batchesAktif = Batch::where('status', 'aktif')->count();

        $dalamPengerjaan = Tugas::where('status', 'dikerjakan')->count();

        return view('pages.tugas.index', compact('batches', 'totalBelumDitugaskan', 'batchesAktif', 'dalamPengerjaan', 'role'));
    }

    /**
     * Show form to create a new task assignment
     */
    public function create(LaporanKerusakan $laporan)
    {

        if (!$laporan->id_batch || !$laporan->batch) {
            $msg = "Laporan id: {$laporan->id_laporan} belum masuk dalam batch manapun.";
            return redirect()->route('tugas.index')
                ->with('error', $msg);
        }


        if ($laporan->batch->status !== 'aktif') {
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
        $teknisi = Pengguna::where('peran', 'teknisi')->get();

        return view('pages.tugas.create', compact('laporan', 'teknisi'));
    }

    /**
     * Store a new task assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required|exists:laporan_kerusakan,id_laporan',
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
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
            $tugas = new Tugas();
            $tugas->id_laporan = $request->id_laporan;
            $tugas->id_pengguna = $request->id_pengguna;
            $tugas->batas_waktu = $request->batas_waktu;
            $tugas->prioritas = $request->prioritas;
            $tugas->catatan = $request->catatan;
            $tugas->status = 'ditugaskan';
            $tugas->save();

            // Update laporan status
            $laporan->status = 'diproses';
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
