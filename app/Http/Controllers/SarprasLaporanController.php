<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKerusakanExport;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SarprasLaporanController extends Controller
{


    public function index(Request $request)
    {
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        $status = $request->get('status', 'menunggu_verifikasi');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        if ($request->filled('ranking')) {
            $query->where('ranking', $request->ranking);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        if (Auth::user()->peran === 'teknisi') {
            $query->whereIn('status', ['diproses', 'diperbaiki']);
        }

        $laporans = $query->latest()->paginate(15);

        $laporans->getCollection()->transform(function ($laporan) {
            $laporan->status_badge_class = match ($laporan->status) {
                'menunggu_verifikasi' => 'bg-warning text-dark',
                'diproses' => 'bg-info text-white',
                'diperbaiki' => 'bg-primary text-white',
                'selesai' => 'bg-success text-white',
                'ditolak' => 'bg-danger text-white',
                default => 'bg-secondary text-muted',
            };
            $laporan->status_label = str_replace('_', ' ', ucwords($laporan->status));
            $laporan->ranking_stars = $laporan->ranking ? str_repeat('★', $laporan->ranking) . str_repeat('☆', 5 - $laporan->ranking) : '-';
            return $laporan;
        });

        $statistik = [
            'menunggu_verifikasi' => LaporanKerusakan::where('status', 'menunggu_verifikasi')->count(),
            'diproses' => LaporanKerusakan::where('status', 'diproses')->count(),
            'diperbaiki' => LaporanKerusakan::where('status', 'diperbaiki')->count(),
            'selesai' => LaporanKerusakan::where('status', 'selesai')->count(),
            'ditolak' => LaporanKerusakan::where('status', 'ditolak')->count(),
        ];

        return view('pages.sarpras.laporan.index', compact('laporans', 'statistik', 'status'));
    }

    public function show(LaporanKerusakan $laporan)
    {
        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        if (request()->ajax()) {
            return response()->json([
                'id_laporan' => $laporan->id_laporan,
                'deskripsi' => $laporan->deskripsi,
                'url_foto' => $laporan->url_foto ? Storage::url($laporan->url_foto) : null,
                'status' => $laporan->status,
                'ranking' => $laporan->ranking,
                'created_at' => $laporan->created_at->format('d/m/Y H:i'),
                'updated_at' => $laporan->updated_at->format('d/m/Y H:i'),
                'pengguna' => [
                    'nama' => $laporan->pengguna->nama_lengkap,
                    'email' => $laporan->pengguna->email,
                ],
                'fasilitasRuang' => [
                    'fasilitas' => [
                        'nama_fasilitas' => $laporan->fasilitasRuang->fasilitas->nama_fasilitas,
                    ],
                    'ruang' => [
                        'nama_ruang' => $laporan->fasilitasRuang->ruang->nama_ruang,
                        'gedung' => [
                            'nama_gedung' => $laporan->fasilitasRuang->ruang->gedung->nama_gedung,
                        ],
                    ],
                    'kode_fasilitas' => $laporan->fasilitasRuang->kode_fasilitas,
                ],
            ]);
        }

        return view('pages.sarpras.laporan.show', compact('laporan'));
    }

    public function verifikasi(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'ranking' => 'nullable|integer|min:0|max:5',
        ]);

        $laporan->update([
            'status' => $request->status,
            'ranking' => $request->ranking,
        ]);

        $message = "Laporan berhasil diverifikasi dengan status: " . str_replace('_', ' ', ucwords($request->status));
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('sarpras.laporan.index')->with('success', $message);
    }

    public function updateStatus(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
            'ranking' => 'nullable|integer|min:0|max:5',
        ]);

        $laporan->update([
            'id_fas_ruang' => $request->id_fas_ruang,
            'status' => $request->status,
            'ranking' => $request->ranking,
        ]);

        $message = "Status laporan berhasil diperbarui ke: " . str_replace('_', ' ', ucwords($request->status));
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'laporan_ids' => 'required|array',
            'laporan_ids.*' => 'exists:laporan_kerusakan,id_laporan',
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
        ]);

        LaporanKerusakan::whereIn('id_laporan', $request->laporan_ids)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        $message = "Berhasil mengupdate status untuk " . count($request->laporan_ids) . " laporan.";
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('sarpras.laporan.index')->with('success', $message);
    }

    public function export(Request $request)
    {
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        if ($request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        return Excel::download(new LaporanKerusakanExport($query->get()), 'laporan_kerusakan_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function update(Request $request, LaporanKerusakan $laporan)
    {
        $validationRules = [
            'deskripsi' => 'required|string|max:255',
            'url_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
        ];

        if ($request->status === 'diproses') {
            $validationRules['tingkat_kerusakan_sarpras'] = 'required|integer|min:1|max:5';
            $validationRules['dampak_akademik_sarpras'] = 'required|integer|min:1|max:5';
            $validationRules['kebutuhan_sarpras'] = 'required|integer|min:1|max:5';
        }

        $request->validate($validationRules);

        try {
            $data = [
                'id_fas_ruang' => $request->id_fas_ruang,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ];

            if ($request->hasFile('url_foto')) {
                if ($laporan->url_foto && Storage::disk('public')->exists($laporan->url_foto)) {
                    Storage::disk('public')->delete($laporan->url_foto);
                }
                $path = $request->file('url_foto')->store('laporan_foto', 'public');
                $data['url_foto'] = $path;
            }

            $laporan->update($data);

            $laporan->kriteria()->updateOrCreate(
                ['id_laporan' => $laporan->id_laporan],
                [
                    'tingkat_kerusakan_sarpras' => $request->tingkat_kerusakan_sarpras,
                    'dampak_akademik_sarpras' => $request->dampak_akademik_sarpras,
                    'kebutuhan_sarpras' => $request->kebutuhan_sarpras,
                    'updated_at' => now(),
                ]
            );

            $message = "Laporan berhasil diupdate.";
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $laporan->fresh()->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'kriteria'])
                ]);
            }

            return redirect()->route('sarpras.laporan.index')->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
