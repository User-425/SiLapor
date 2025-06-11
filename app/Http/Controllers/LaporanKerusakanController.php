<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKerusakanExport;
use App\Notifications\NewReportNotification;
use App\Notifications\StatusChangedNotification;
use App\Notifications\FeedbackRequestNotification;
use App\Models\Pengguna;
use App\Models\LaporanKerusakan;
use App\Models\FasRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKerusakanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Hapus middleware peran dari constructor, karena kita handle di routes dan method
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('fasilitasRuang.ruang', function ($r) use ($searchTerm) {
                    $r->where('nama_ruang', 'like', '%' . $searchTerm . '%');
                })
                    ->orWhereHas('fasilitasRuang.fasilitas', function ($f) use ($searchTerm) {
                        $f->where('nama_fasilitas', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('fasilitasRuang', function ($fr) use ($searchTerm) {
                        $fr->where('kode_fasilitas', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        // Logic berdasarkan role
        if (in_array($user->peran, ['mahasiswa', 'dosen', 'tendik'])) {
            // User biasa: hanya lihat laporan sendiri yang belum selesai
            $query->where('id_pengguna', $user->id_pengguna)
                ->where('status', '!=', 'selesai');

            $laporans = $query->latest()->paginate(10);
            return view('pages.laporan.index', compact('laporans'));
        }

        // Logic untuk sarpras/teknisi (bisa melihat semua laporan)
        $query->where('status', '!=', 'selesai');
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

        if ($user->peran === 'teknisi') {
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

        return view('pages.laporan.sarpras-index', compact('laporans', 'statistik', 'status'));
    }

    public function create()
    {
        $gedungs = \App\Models\Gedung::all();
        return view('pages.laporan.create', compact('gedungs'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_fas_ruang' => 'required|exists:fasilitas_ruang,id_fas_ruang',
                'deskripsi' => 'required|string|max:255',
                'url_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'tingkat_kerusakan_pelapor' => 'required|integer|min:1|max:5',
                'dampak_akademik_pelapor' => 'required|integer|min:1|max:5',
                'kebutuhan_pelapor' => 'required|integer|min:1|max:5',
            ]);

            $directory = storage_path('app/public/laporan_foto');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $data = [
                'id_pengguna' => Auth::id(),
                'id_fas_ruang' => $request->id_fas_ruang,
                'deskripsi' => $request->deskripsi,
                'status' => 'menunggu_verifikasi',
                'ranking' => 0,
            ];

            if ($request->hasFile('url_foto')) {
                $file = $request->file('url_foto');
                $path = $file->store('laporan_foto', 'public');
                $data['url_foto'] = $path;
            }

            $laporan = LaporanKerusakan::create($data);

            DB::table('kriteria_laporan')->insert([
                'id_laporan' => $laporan->id_laporan,
                'tingkat_kerusakan_pelapor' => $request->tingkat_kerusakan_pelapor,
                'dampak_akademik_pelapor' => $request->dampak_akademik_pelapor,
                'kebutuhan_pelapor' => $request->kebutuhan_pelapor,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sarprasUsers = Pengguna::where('peran', 'sarpras')->get();
            foreach ($sarprasUsers as $user) {
                $user->notify(new NewReportNotification($laporan->load([
                    'fasilitasRuang.fasilitas',
                    'fasilitasRuang.ruang',
                    'pengguna'
                ])));
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil disimpan'
                ]);
            }

            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil disimpan');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(LaporanKerusakan $laporan)
    {
        $user = Auth::user();

        // Cek authorization: pemilik laporan atau sarpras/teknisi
        if ($laporan->id_pengguna !== $user->id_pengguna && !in_array($user->peran, ['sarpras', 'teknisi'])) {
            abort(403, 'Anda tidak memiliki akses untuk melihat laporan ini.');
        }

        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);
        return view('pages.laporan.show', compact('laporan'));
    }

    public function edit(LaporanKerusakan $laporan)
    {
        $user = Auth::user();

        if ($laporan->status === 'selesai') {
            return redirect()->back()->with('error', 'Laporan yang sudah selesai tidak dapat diubah.');
        }

        // Cek authorization: pemilik laporan atau sarpras
        if ($laporan->id_pengguna !== $user->id_pengguna && $user->peran !== 'sarpras') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        $gedungs = \App\Models\Gedung::all();
        return view('pages.laporan.edit', compact('laporan', 'gedungs'));
    }

    public function update(Request $request, LaporanKerusakan $laporan)
    {
        if ($laporan->status === 'selesai') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Laporan yang sudah selesai tidak dapat diubah.'], 403);
            }
            return redirect()->back()->with('error', 'Laporan yang sudah selesai tidak dapat diubah.');
        }

        if ($laporan->id_pengguna !== Auth::id() && Auth::user()->peran !== 'sarpras') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'url_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $data = [
                'id_fas_ruang' => $request->id_fas_ruang,
                'deskripsi' => $request->deskripsi,
            ];

            if ($request->hasFile('url_foto')) {
                if ($laporan->url_foto && Storage::disk('public')->exists($laporan->url_foto)) {
                    Storage::disk('public')->delete($laporan->url_foto);
                }
                $path = $request->file('url_foto')->store('laporan_foto', 'public');
                $data['url_foto'] = $path;
            }

            $laporan->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil diupdate.',
                'data' => $laporan->fresh()->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(LaporanKerusakan $laporan)
    {
        $user = Auth::user();

        // Cek authorization: pemilik laporan atau sarpras
        if ($laporan->id_pengguna !== $user->id_pengguna && $user->peran !== 'sarpras') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan ini.');
        }

        if ($laporan->url_foto && Storage::disk('public')->exists($laporan->url_foto)) {
            Storage::disk('public')->delete($laporan->url_foto);
        }
        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function getFasilitasByRuang($ruang_id)
    {
        $fasilitas = FasRuang::with('fasilitas')
            ->where('id_ruang', $ruang_id)
            ->get()
            ->groupBy('id_fasilitas')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'id_fasilitas' => $first->id_fasilitas,
                    'nama_fasilitas' => $first->fasilitas->nama_fasilitas ?? '-',
                ];
            })
            ->values();
        return response()->json($fasilitas);
    }

    public function getKodeByRuangFasilitas($ruang_id, $fasilitas_id)
    {
        $kode = FasRuang::where('id_ruang', $ruang_id)
            ->where('id_fasilitas', $fasilitas_id)
            ->get(['id_fas_ruang', 'kode_fasilitas']);
        return response()->json($kode);
    }

    public function getDetail(LaporanKerusakan $laporan)
    {
        if ($laporan->id_pengguna !== Auth::id() && !in_array(Auth::user()->peran, ['sarpras', 'teknisi'])) {
            abort(403);
        }

        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna', 'kriteria']);

        $statusLabel = str_replace('_', ' ', ucwords($laporan->status));
        $statusBadgeClass = match ($laporan->status) {
            'menunggu_verifikasi' => 'bg-warning text-dark',
            'diproses' => 'bg-info text-white',
            'diperbaiki' => 'bg-primary text-white',
            'selesai' => 'bg-success text-white',
            'ditolak' => 'bg-danger text-white',
            default => 'bg-secondary text-muted',
        };

        return response()->json([
            'id_laporan' => $laporan->id_laporan,
            'deskripsi' => $laporan->deskripsi,
            'url_foto' => $laporan->url_foto ? asset('storage/' . $laporan->url_foto) : null,
            'status' => $laporan->status,
            'status_label' => $statusLabel,
            'status_badge_class' => $statusBadgeClass,
            'ranking' => $laporan->ranking,
            'created_at' => $laporan->created_at->format('d/m/Y H:i'),
            'updated_at' => $laporan->updated_at->format('d/m/Y H:i'),
            'kriteria' => $laporan->kriteria, // Add criteria data to response
            'fasilitasRuang' => [
                'id_fas_ruang' => $laporan->fasilitasRuang->id_fas_ruang,
                'kode_fasilitas' => $laporan->fasilitasRuang->kode_fasilitas,
                'fasilitas' => [
                    'id_fasilitas' => $laporan->fasilitasRuang->fasilitas->id_fasilitas,
                    'nama_fasilitas' => $laporan->fasilitasRuang->fasilitas->nama_fasilitas,
                ],
                'ruang' => [
                    'id_ruang' => $laporan->fasilitasRuang->ruang->id_ruang,
                    'nama_ruang' => $laporan->fasilitasRuang->ruang->nama_ruang,
                    'gedung' => [
                        'id_gedung' => $laporan->fasilitasRuang->ruang->gedung->id,
                        'nama_gedung' => $laporan->fasilitasRuang->ruang->gedung->nama_gedung,
                    ],
                ],
            ],
            'pengguna' => [
                'nama_lengkap' => $laporan->pengguna->nama_lengkap ?? 'Tidak diketahui',
                'email' => $laporan->pengguna->email,
            ],
        ]);
    }

    public function verifikasi(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:diproses,ditolak',
            'ranking' => 'nullable|integer|min:0|max:5',
        ]);

        $oldStatus = $laporan->status;

        $laporan->update([
            'status' => $request->status,
            'ranking' => $request->ranking ?? 0,
        ]);

        $laporan->pengguna->notify(
            new StatusChangedNotification(
                $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang']),
                $oldStatus,
                $request->status
            )
        );

        $message = "Laporan berhasil diverifikasi dengan status: " . str_replace('_', ' ', ucwords($request->status));
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('laporan.index')->with('success', $message);
    }

    public function updateStatus(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
            'ranking' => 'nullable|integer|min:0|max:5',
        ]);

        $oldStatus = $laporan->status;

        $laporan->update([
            'status' => $request->status,
            'ranking' => $request->ranking,
        ]);

        $laporan->pengguna->notify(
            new StatusChangedNotification(
                $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang']),
                $oldStatus,
                $request->status
            )
        );

        if ($request->status === 'selesai') {
            $laporan->pengguna->notify(
                new FeedbackRequestNotification(
                    $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang'])
                )
            );
        }

        $message = "Status laporan berhasil diperbarui ke: " . str_replace('_', ' ', ucwords($request->status));
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('laporan.index')->with('success', $message);
    }

    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'laporan_ids' => 'required|array',
            'laporan_ids.*' => 'exists:laporan_kerusakan,id_laporan',
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
        ]);

        $laporans = LaporanKerusakan::whereIn('id_laporan', $request->laporan_ids)->get();

        LaporanKerusakan::whereIn('id_laporan', $request->laporan_ids)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        foreach ($laporans as $laporan) {
            $oldStatus = $laporan->status;

            $laporan->refresh();

            $laporan->pengguna->notify(
                new StatusChangedNotification(
                    $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang']),
                    $oldStatus,
                    $request->status
                )
            );

            if ($request->status === 'selesai') {
                $laporan->pengguna->notify(
                    new FeedbackRequestNotification(
                        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang'])
                    )
                );
            }
        }

        $message = "Berhasil mengupdate status untuk " . count($request->laporan_ids) . " laporan.";
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil disimpan');
    }

    public function export(Request $request)
    {
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        if ($request->status && $request->status !== 'semua') {
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

    public function quickReport($code)
    {
        try {
            $id = base64_decode($code);
            $fasRuang = FasRuang::with(['fasilitas', 'ruang.gedung'])
                ->findOrFail($id);

            $userRole = Auth::user()->peran;

            // Redirect based on user role
            switch ($userRole) {
                case 'admin':
                    return redirect()->route('fasilitas.show', $id);

                case 'sarpras':
                    return redirect()->route('fasilitas.history', $id);

                case 'teknisi':
                    return redirect()->route('fasilitas.maintenance', $id);

                default:
                    return view('pages.laporan.quick-report', [
                        'fasRuang' => $fasRuang
                    ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('laporan.create')
                ->with('error', 'QR Code tidak valid. Silakan laporkan secara manual.');
        }
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna'])
            ->where('status', 'selesai');

        // Jika bukan admin/sarpras/teknisi, hanya tampilkan riwayat laporan sendiri
        if (!in_array($user->peran, ['admin', 'sarpras', 'teknisi'])) {
            $query->where('id_pengguna', $user->id_pengguna);
        }

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('fasilitasRuang.ruang', function ($r) use ($searchTerm) {
                    $r->where('nama_ruang', 'like', '%' . $searchTerm . '%');
                })
                    ->orWhereHas('fasilitasRuang.fasilitas', function ($f) use ($searchTerm) {
                        $f->where('nama_fasilitas', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('fasilitasRuang', function ($fr) use ($searchTerm) {
                        $fr->where('kode_fasilitas', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        try {
            $laporans = $query->latest()->paginate(10);
        } catch (\Exception $e) {
            \Log::error('Error loading riwayat: ' . $e->getMessage());
            return view('pages.laporan.riwayat', ['laporans' => collect([])])->with('error', 'Gagal memuat data riwayat');
        }

        return view('pages.laporan.riwayat', compact('laporans'));
    }
}
