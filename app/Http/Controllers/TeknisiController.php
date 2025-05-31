<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();

            $request->validate([
                'catatan' => 'required|string|max:1000',
                'status' => 'required|in:dikerjakan,selesai'
            ]);

            $tugas = Tugas::with('laporan')->findOrFail($id);
            
            // Verify ownership
            if ($tugas->id_pengguna !== Auth::id()) {
                throw new \Exception('Unauthorized access');
            }

            $updateData = [
                'catatan' => $request->catatan,
                'status' => $request->status,
                'tanggal_selesai' => $request->status === 'selesai' ? now() : null
            ];

            $tugas->update($updateData);

            // Update related laporan status
            $tugas->laporan->update([
                'status' => $request->status === 'selesai' ? 'selesai' : 'diperbaiki'
            ]);

            DB::commit();

            $message = 'Status tugas berhasil diperbarui';
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('teknisi.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}