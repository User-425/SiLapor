<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\MabacService;

class BatchController extends Controller
{
    protected $mabacService;

    public function __construct(MabacService $mabacService)
    {
        $this->middleware('auth');
        $this->middleware('peran:sarpras');
        $this->mabacService = $mabacService;
    }

    /**
     * Display a listing of batches
     */
    public function index()
    {
        $batches = Batch::orderBy('created_at', 'desc')->get();
        
        // Get count of unbatched reports for display
        $unbatchedReportsCount = LaporanKerusakan::whereNull('id_batch')
            ->where('status', 'menunggu_verifikasi')
            ->count();
        
        return view('pages.batch.index', compact('batches', 'unbatchedReportsCount'));
    }

    /**
     * Show the form for creating a new batch
     */
    public function create()
    {
        // Get all reports that aren't assigned to a batch and aren't completed/rejected
        $availableReports = LaporanKerusakan::whereNull('id_batch')
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna'])
            ->get();
        
        return view('pages.batch.create', compact('availableReports'));
    }

    /**
     * Store a newly created batch in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_batch' => 'required|string|max:255',
            'selected_reports' => 'required|array',
            'selected_reports.*' => 'exists:laporan_kerusakan,id_laporan',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Create the batch
            $batch = Batch::create([
                'nama_batch' => $request->nama_batch,
                'status' => 'draft',
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'catatan' => $request->catatan,
            ]);
            
            // Assign selected reports to this batch
            LaporanKerusakan::whereIn('id_laporan', $request->selected_reports)
                ->update(['id_batch' => $batch->id_batch]);
                
            DB::commit();
            
            return redirect()->route('batches.show', $batch->id_batch)
                ->with('success', 'Batch berhasil dibuat dengan ' . count($request->selected_reports) . ' laporan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat batch: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified batch
     */
    public function show(Batch $batch)
    {
        $batch->load(['laporans.fasilitasRuang.fasilitas', 'laporans.fasilitasRuang.ruang.gedung', 'laporans.pengguna', 'laporans.kriteria']);
        
        // Group reports by status
        $laporansByStatus = $batch->laporans->groupBy('status');
        
        // Get available reports that could be added to this batch
        $availableReports = LaporanKerusakan::whereNull('id_batch')
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->count();
        
        return view('pages.batch.show', compact('batch', 'laporansByStatus', 'availableReports'));
    }

    /**
     * Activate a batch
     */
    public function activate(Batch $batch)
    {
        if ($batch->status !== 'draft') {
            return redirect()->back()->with('error', 'Hanya batch dengan status draft yang dapat diaktifkan.');
        }
        
        $batch->status = 'aktif';
        $batch->tanggal_mulai = $batch->tanggal_mulai ?? Carbon::today();
        $batch->save();
        
        // Update all reports in this batch to 'diproses' if they're still in 'menunggu_verifikasi'
        LaporanKerusakan::where('id_batch', $batch->id_batch)
            ->where('status', 'menunggu_verifikasi')
            ->update(['status' => 'diproses']);
        
        return redirect()->route('batches.show', $batch->id_batch)
            ->with('success', 'Batch berhasil diaktifkan dan semua laporan diproses.');
    }

    /**
     * Complete a batch
     */
    public function complete(Batch $batch)
    {
        if ($batch->status !== 'aktif') {
            return redirect()->back()->with('error', 'Hanya batch dengan status aktif yang dapat diselesaikan.');
        }
        
        $batch->status = 'selesai';
        $batch->tanggal_selesai = Carbon::today();
        $batch->save();
        
        return redirect()->route('batches.index')
            ->with('success', 'Batch berhasil diselesaikan.');
    }

    /**
     * Add reports to a batch
     */
    public function addReports(Request $request, Batch $batch)
    {
        $request->validate([
            'selected_reports' => 'required|array',
            'selected_reports.*' => 'exists:laporan_kerusakan,id_laporan',
        ]);
        
        if ($batch->status === 'selesai') {
            return redirect()->back()->with('error', 'Tidak dapat menambahkan laporan ke batch yang sudah selesai.');
        }
        
        LaporanKerusakan::whereIn('id_laporan', $request->selected_reports)
            ->update(['id_batch' => $batch->id_batch]);
        
        return redirect()->route('batches.show', $batch->id_batch)
            ->with('success', 'Berhasil menambahkan ' . count($request->selected_reports) . ' laporan ke batch.');
    }

    /**
     * Show form to add more reports to a batch
     */
    public function showAddReports(Batch $batch)
    {
        if ($batch->status === 'selesai') {
            return redirect()->route('batches.show', $batch->id_batch)
                ->with('error', 'Tidak dapat menambahkan laporan ke batch yang sudah selesai.');
        }
        
        $availableReports = LaporanKerusakan::whereNull('id_batch')
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna'])
            ->get();
        
        return view('pages.batch.add-reports', compact('batch', 'availableReports'));
    }
    
    /**
     * Remove a report from a batch
     */
    public function removeReport(Request $request, Batch $batch, LaporanKerusakan $laporan)
    {
        if ($batch->status === 'selesai') {
            return redirect()->back()->with('error', 'Tidak dapat mengubah batch yang sudah selesai.');
        }
        
        if ($laporan->id_batch !== $batch->id_batch) {
            return redirect()->back()->with('error', 'Laporan tidak termasuk dalam batch ini.');
        }
        
        $laporan->id_batch = null;
        $laporan->save();
        
        return redirect()->route('batches.show', $batch->id_batch)
            ->with('success', 'Laporan berhasil dikeluarkan dari batch.');
    }

    /**
     * Show the MABAC ranking page for a batch
     */
    public function showRankingPage(Batch $batch)
    {
        if ($batch->status === 'selesai') {
            return redirect()->route('batches.show', $batch->id_batch)
                ->with('error', 'Batch yang sudah selesai tidak dapat diubah prioritasnya.');
        }
        
        // Load reports with criteria
        $reports = LaporanKerusakan::where('id_batch', $batch->id_batch)
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'kriteria'])
            ->get();
        
        // Calculate priorities using MABAC
        $result = $this->mabacService->calculatePriorities($reports);
        $rankedReports = $result['reports'];
        
        return view('pages.batch.ranking', compact('batch', 'rankedReports'));
    }

    /**
     * Save the MABAC rankings to database
     */
    public function saveRankings(Request $request, Batch $batch)
    {
        $request->validate([
            'rankings' => 'required|array',
            'rankings.*' => 'required|numeric',
        ]);
        
        DB::beginTransaction();
        
        try {
            foreach ($request->rankings as $laporan_id => $ranking) {
                LaporanKerusakan::where('id_laporan', $laporan_id)
                    ->update(['ranking' => $ranking]);
            }
            
            DB::commit();
            return redirect()->route('batches.show', $batch->id_batch)
                ->with('success', 'Prioritas laporan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan prioritas: ' . $e->getMessage());
        }
    }

    /**
     * Show detailed MABAC calculations for a batch
     */
    public function showCalculations(Batch $batch)
    {
        if ($batch->status === 'selesai') {
            return redirect()->route('batches.show', $batch->id_batch)
                ->with('error', 'Batch yang sudah selesai tidak dapat diubah prioritasnya.');
        }
        
        // Load reports with criteria
        $reports = LaporanKerusakan::where('id_batch', $batch->id_batch)
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'kriteria'])
            ->get();
        
        // If no reports found
        if ($reports->count() == 0) {
            return redirect()->route('batches.show', $batch->id_batch)
                ->with('error', 'Tidak ada laporan yang dapat diproses dalam batch ini.');
        }
        
        // Calculate priorities using MABAC
        $result = $this->mabacService->calculatePriorities($reports);
        $rankedReports = $result['reports'];
        $calculationSteps = $result['steps'];
        
        // Map report IDs to report objects for display
        $reportsById = [];
        foreach ($reports as $report) {
            $reportsById[$report->id_laporan] = $report;
        }
        
        return view('pages.batch.calculations', compact('batch', 'rankedReports', 'calculationSteps', 'reportsById'));
    }
}