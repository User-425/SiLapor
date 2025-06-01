<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\StatusChangedNotification;
use App\Notifications\FeedbackRequestNotification;
use App\Models\Pengguna;

class TeknisiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'peran:teknisi']);
    }

    public function index()
    {
        try {
            $teknisiId = Auth::id();
            
            $tugas = Tugas::with([
                'laporan.fasilitasRuang.fasilitas',
                'laporan.fasilitasRuang.ruang.gedung',
                'laporan.pengguna'
            ])
            ->where('id_pengguna', $teknisiId)
            ->whereIn('status', ['ditugaskan', 'dikerjakan'])
            ->latest()
            ->get();

            // Group tasks by priority
            $tugasByPriority = $tugas->groupBy('prioritas');

            return view('pages.teknisi.index', compact('tugasByPriority'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayatPerbaikan(Request $request)
    {
        try {
            $query = Tugas::with([
                'laporan.fasilitasRuang.fasilitas',
                'laporan.fasilitasRuang.ruang.gedung',
                'laporan.pengguna'
            ])
            ->where('id_pengguna', Auth::id())
            ->where('status', 'selesai');

            // Filter by date range if provided
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_selesai', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_selesai', '<=', $request->tanggal_sampai);
            }

            $riwayat = $query->latest('tanggal_selesai')
                            ->paginate(10)
                            ->withQueryString();

            return view('pages.teknisi.riwayat_laporan', compact('riwayat'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateTugas(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ditugaskan,dalam_pengerjaan,selesai,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        $tugas = Tugas::findOrFail($id);
        
        // Ensure the technician is the assigned one
        if ($tugas->id_pengguna != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $oldStatus = $tugas->status;
        $tugas->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'updated_at' => now(),
        ]);

        // Update report status based on task status
        $laporan = $tugas->laporan;
        $oldLaporanStatus = $laporan->status;
        $newLaporanStatus = match($request->status) {
            'dalam_pengerjaan' => 'diperbaiki',
            'selesai' => 'selesai',
            default => $laporan->status
        };
        
        if ($oldLaporanStatus != $newLaporanStatus) {
            $laporan->update(['status' => $newLaporanStatus]);
            
            // Notify report creator about status change
            $laporan->pengguna->notify(
                new StatusChangedNotification(
                    $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang']), 
                    $oldLaporanStatus, 
                    $newLaporanStatus
                )
            );
            
            // If task is completed, request feedback
            if ($request->status === 'selesai') {
                $laporan->pengguna->notify(
                    new FeedbackRequestNotification(
                        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang'])
                    )
                );
            }
        }

        // Notify Sarpras about task status update
        $sarprasUsers = Pengguna::where('peran', 'sarpras')->get();
        foreach ($sarprasUsers as $user) {
            $user->notify(new StatusChangedNotification(
                $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang']), 
                $oldLaporanStatus, 
                $newLaporanStatus
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Status tugas berhasil diperbarui menjadi ' . str_replace('_', ' ', ucwords($request->status)),
        ]);
    }
}