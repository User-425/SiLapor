<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SarprasLaporanController extends Controller
{
    // Dashboard daftar laporan untuk Sarana Prasarana
    public function index(Request $request)
    {
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang', 'pengguna']);
        
        // Filter berdasarkan status
        $status = $request->get('status', 'menunggu_verifikasi');
        
        switch ($status) {
            case 'menunggu_verifikasi':
                $query->where('status', 'menunggu_verifikasi');
                break;
            case 'diproses':
                $query->where('status', 'diproses');
                break;
            case 'diperbaiki':
                $query->where('status', 'diperbaiki');
                break;
            case 'selesai':
                $query->where('status', 'selesai');
                break;
            case 'ditolak':
                $query->where('status', 'ditolak');
                break;
            case 'semua':
                // Tampilkan semua
                break;
            default:
                $query->where('status', 'menunggu_verifikasi');
        }

        // Filter berdasarkan ranking jika ada
        if ($request->filled('ranking')) {
            $query->where('ranking', $request->ranking);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $laporans = $query->latest()->paginate(15);

        // Hitung statistik
        $statistik = [
            'menunggu_verifikasi' => LaporanKerusakan::where('status', 'menunggu_verifikasi')->count(),
            'diproses' => LaporanKerusakan::where('status', 'diproses')->count(),
            'diperbaiki' => LaporanKerusakan::where('status', 'diperbaiki')->count(),
            'selesai' => LaporanKerusakan::where('status', 'selesai')->count(),
            'ditolak' => LaporanKerusakan::where('status', 'ditolak')->count(),
        ];

        return view('pages.sarpras.index', compact('laporans', 'statistik', 'status'));
    }

    // Tampilkan detail laporan untuk verifikasi
    public function show(LaporanKerusakan $laporan)
    {
        $laporan->load([
            'fasilitasRuang.fasilitas',
            'fasilitasRuang.ruang',
            'pengguna'
        ]);

        if (request()->ajax()) {
            $responseData = [
                'id_laporan' => $laporan->id_laporan,
                'deskripsi' => $laporan->deskripsi,
                'url_foto' => $laporan->url_foto,
                'status' => $laporan->status,
                'ranking' => $laporan->ranking,
                'created_at' => $laporan->created_at->format('d/m/Y H:i'),
                'updated_at' => $laporan->updated_at->format('d/m/Y H:i'),
                'pengguna' => [
                    'id' => $laporan->id_pengguna,
                    'nama' => $laporan->pengguna->nama ?? 'Tidak diketahui',
                    'email' => $laporan->pengguna->email ?? '-',
                    'role' => $laporan->pengguna->role ?? '-',
                ],
                'fasilitasRuang' => [
                    'id_fas_ruang' => $laporan->fasilitasRuang->id_fas_ruang,
                    'kode_fasilitas' => $laporan->fasilitasRuang->kode_fasilitas ?? '-',
                    'fasilitas' => [
                        'nama_fasilitas' => $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? '-',
                    ],
                    'ruang' => [
                        'nama_ruang' => $laporan->fasilitasRuang->ruang->nama_ruang ?? '-',
                    ],
                ],
            ];

            return response()->json($responseData);
        }

        return view('pages.sarpras.show', compact('laporan'));
    }

    // Verifikasi laporan
    public function verifikasi(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'ranking' => 'nullable|integer|min:1|max:5',
        ]);

        // Update status berdasarkan pilihan
        $statusMap = [
            'diproses' => 'diproses',
            'selesai' => 'selesai',
            'ditolak' => 'ditolak'
        ];

        $laporan->update([
            'status' => $statusMap[$request->status],
            'ranking' => $request->ranking ?? $laporan->ranking,
            'updated_at' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil diverifikasi dengan status: ' . $statusMap[$request->status]
            ]);
        }

        return redirect()->route('sarpras.laporan.index')
                        ->with('success', 'Laporan berhasil diverifikasi dengan status: ' . $statusMap[$request->status]);
    }

    // Edit/update status dan ranking laporan
    public function updateStatus(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
            'ranking' => 'nullable|integer|min:1|max:5',
        ]);

        $laporan->update([
            'status' => $request->status,
            'ranking' => $request->ranking ?? $laporan->ranking,
            'updated_at' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status laporan berhasil diperbarui.'
            ]);
        }

        return redirect()->route('sarpras.laporan.index')
                        ->with('success', 'Status laporan berhasil diperbarui.');
    }

    // Update ranking laporan berdasarkan prioritas
    public function updateRanking(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'ranking' => 'required|integer|min:1|max:5',
        ]);

        $laporan->update([
            'ranking' => $request->ranking,
            'updated_at' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ranking laporan berhasil diperbarui.',
                'ranking' => $laporan->ranking
            ]);
        }

        return redirect()->route('sarpras.laporan.index')
                        ->with('success', 'Ranking laporan berhasil diperbarui.');
    }

    // Proses batch update status untuk multiple laporan
    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'laporan_ids' => 'required|array',
            'laporan_ids.*' => 'exists:laporan_kerusakan,id_laporan',
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
        ]);

        $updated = LaporanKerusakan::whereIn('id_laporan', $request->laporan_ids)
                                  ->update([
                                      'status' => $request->status,
                                      'updated_at' => now()
                                  ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Berhasil mengupdate {$updated} laporan dengan status: {$request->status}"
            ]);
        }

        return redirect()->route('sarpras.laporan.index')
                        ->with('success', "Berhasil mengupdate {$updated} laporan dengan status: {$request->status}");
    }

    // Selesaikan perbaikan
    public function selesaikanPerbaikan(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'catatan_penyelesaian' => 'nullable|string|max:1000',
            'biaya_aktual' => 'nullable|numeric|min:0',
            'url_foto_setelah' => 'nullable|file|image|max:2048',
        ]);

        $data = [
            'status' => 'Selesai',
            'catatan_penyelesaian' => $request->catatan_penyelesaian,
            'biaya_aktual' => $request->biaya_aktual,
            'tanggal_selesai' => now(),
            'petugas_penyelesai_id' => auth()->id(),
        ];

        // Handle file upload foto setelah perbaikan
        if ($request->hasFile('url_foto_setelah')) {
            $file = $request->file('url_foto_setelah');
            $path = $file->store('laporan_selesai', 'public');
            $data['url_foto_setelah'] = $path;
        }

        $laporan->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perbaikan berhasil diselesaikan.'
            ]);
        }

        return redirect()->route('sarpras.laporan.index')
                        ->with('success', 'Perbaikan berhasil diselesaikan.');
    }

    // Tambah catatan internal
    public function tambahCatatan(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'catatan_internal' => 'required|string|max:1000',
        ]);

        $catatanLama = $laporan->catatan_internal ?? '';
        $catatanBaru = $catatanLama . "\n[" . now()->format('d/m/Y H:i') . " - " . auth()->user()->nama . "]\n" . $request->catatan_internal;

        $laporan->update([
            'catatan_internal' => $catatanBaru,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catatan internal berhasil ditambahkan.',
                'catatan_internal' => $catatanBaru
            ]);
        }

        return redirect()->back()->with('success', 'Catatan internal berhasil ditambahkan.');
    }

    // Export laporan ke Excel/PDF
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $status = $request->get('status');
        $tanggal_dari = $request->get('tanggal_dari');
        $tanggal_sampai = $request->get('tanggal_sampai');

        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang', 'pengguna']);

        if ($status && $status !== 'semua') {
            $statusMap = [
                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                'sedang_diproses' => 'Sedang Diproses',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
            ];
            $query->where('status', $statusMap[$status] ?? $status);
        }

        if ($tanggal_dari) {
            $query->whereDate('created_at', '>=', $tanggal_dari);
        }
        if ($tanggal_sampai) {
            $query->whereDate('created_at', '<=', $tanggal_sampai);
        }

        $laporans = $query->get();

        if ($format === 'pdf') {
            // Logic untuk export PDF
            return response()->json(['message' => 'Export PDF belum diimplementasikan']);
        } else {
            // Logic untuk export Excel
            return response()->json(['message' => 'Export Excel belum diimplementasikan']);
        }
    }

    // Get statistik untuk dashboard
    public function getStatistik()
    {
        $statistik = [
            'total_laporan' => LaporanKerusakan::count(),
            'menunggu_verifikasi' => LaporanKerusakan::where('status', 'Menunggu Verifikasi')->count(),
            'sedang_diproses' => LaporanKerusakan::where('status', 'Sedang Diproses')->count(),
            'dalam_perbaikan' => LaporanKerusakan::where('status', 'Dalam Perbaikan')->count(),
            'selesai' => LaporanKerusakan::where('status', 'Selesai')->count(),
            'ditolak' => LaporanKerusakan::where('status', 'Ditolak')->count(),
            'laporan_bulan_ini' => LaporanKerusakan::whereMonth('created_at', now()->month)
                                                  ->whereYear('created_at', now()->year)
                                                  ->count(),
            'rata_waktu_penyelesaian' => LaporanKerusakan::where('status', 'Selesai')
                                                        ->whereNotNull('tanggal_selesai')
                                                        ->avg(DB::raw('DATEDIFF(tanggal_selesai, created_at)')),
        ];

        return response()->json($statistik);
    }
}