<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKerusakanExport;
use App\Models\LaporanKerusakan;
use App\Models\FasRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

use function Laravel\Prompts\error;

class LaporanKerusakanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('peran:sarpras')->only(['verifikasi', 'batchUpdateStatus', 'export']);
        $this->middleware('peran:sarpras,teknisi')->only(['updateStatus']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        // Jika bukan sarpras atau teknisi, hanya tampilkan laporan milik pengguna
        if (!in_array($user->peran, ['sarpras', 'teknisi'])) {
            $query->where('id_pengguna', $user->id_pengguna);
            $laporans = $query->latest()->paginate(10);
            return view('pages.laporan.index', compact('laporans'));
        }

        // Untuk sarpras dan teknisi
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
        $request->validate([
            'id_fas_ruang' => 'required|exists:fasilitas_ruang,id_fas_ruang',
            'deskripsi' => 'required|string|max:255',
            'url_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'id_pengguna' => Auth::id(),
            'id_fas_ruang' => $request->id_fas_ruang,
            'deskripsi' => $request->deskripsi,
            'status' => 'menunggu_verifikasi',
            'ranking' => 0,
        ];

        if ($request->hasFile('url_foto')) {
            $data['url_foto'] = $request->file('url_foto')->store('laporan_foto', 'public');
        }

        LaporanKerusakan::create($data);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function show(LaporanKerusakan $laporan)
    {
        if ($laporan->id_pengguna !== Auth::id() && !in_array(Auth::user()->peran, ['sarpras', 'teknisi'])) {
            abort(403);
        }

        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);
        return view('pages.laporan.show', compact('laporan'));
    }

    public function edit(LaporanKerusakan $laporan)
    {
        if ($laporan->id_pengguna !== Auth::id() && Auth::user()->peran !== 'sarpras') {
            abort(403);
        }
        $gedungs = \App\Models\Gedung::all();
        return view('pages.laporan.edit', compact('laporan', 'gedungs'));
    }

    public function update(Request $request, LaporanKerusakan $laporan)
    {
        if ($laporan->id_pengguna !== Auth::id() && Auth::user()->peran !== 'sarpras') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'id_fas_ruang' => 'required|exists:fasilitas_ruang,id_fas_ruang',
            'deskripsi' => 'required|string',
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
                $path = $request->file('url_foto')->store('laporan-photos', 'public');
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
        if ($laporan->id_pengguna !== Auth::id() && Auth::user()->peran !== 'sarpras') {
            abort(403);
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

        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna']);

        return response()->json([
            'id_laporan' => $laporan->id_laporan,
            'deskripsi' => $laporan->deskripsi,
            'url_foto' => asset('storage/' . $laporan->url_foto), // Fix the URL
            'status' => $laporan->status,
            'ranking' => $laporan->ranking,
            'created_at' => $laporan->created_at->format('d/m/Y H:i'),
            'updated_at' => $laporan->updated_at->format('d/m/Y H:i'),
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
                        'id_gedung' => $laporan->fasilitasRuang->ruang->gedung->id_gedung,
                        'nama_gedung' => $laporan->fasilitasRuang->ruang->gedung->nama_gedung,
                    ],
                ],
            ],
            'pengguna' => [
                'nama' => $laporan->pengguna->nama_lengkap,
                'email' => $laporan->pengguna->email,
            ],
        ]);
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

        return redirect()->route('laporan.index')->with('success', $message);
    }

    public function updateStatus(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,diproses,diperbaiki,selesai,ditolak',
            'ranking' => 'nullable|integer|min:0|max:5',
        ]);

        $laporan->update([
            'status' => $request->status,
            'ranking' => $request->ranking,
        ]);

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

        LaporanKerusakan::whereIn('id_laporan', $request->laporan_ids)->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        $message = "Berhasil mengupdate status untuk " . count($request->laporan_ids) . " laporan.";
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('laporan.index')->with('success', $message);
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

            return view('pages.laporan.quick-report', [ // Changed view path
                'fasRuang' => $fasRuang
            ]);
        } catch (\Exception $e) {
            return redirect()->route('laporan.create')
                ->with('error', 'QR Code tidak valid. Silakan laporkan secara manual.');
        }
    }
}
