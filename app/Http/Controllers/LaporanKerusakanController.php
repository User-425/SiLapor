<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanKerusakanController extends Controller
{
    // Tampilkan semua laporan kerusakan
    public function index()
    {
        // Hanya ambil laporan milik user yang sedang login
        $laporans = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang'])
        ->where('id_pengguna', auth()->id())
        ->latest()
        ->paginate(10);

        return view('pages.laporan.index', compact('laporans'));
    }

    // Tampilkan form tambah laporan
    public function create()
    {
        return view('pages.laporan.create');
    }

    // Simpan laporan baru
    public function store(Request $request)
    {
        $request->validate([
            'id_fas_ruang' => 'required|integer',
            'deskripsi' => 'required|string',
            'url_foto' => 'nullable|file|image|max:2048', // max 2MB
        ]);

        $data = $request->only(['id_fas_ruang', 'deskripsi']);
        $data['id_pengguna'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('url_foto')) {
            $file = $request->file('url_foto');
            $path = $file->store('laporan_foto', 'public');
            $data['url_foto'] = $path;
        }

        LaporanKerusakan::create($data);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    // Tampilkan detail laporan
    public function show(LaporanKerusakan $laporan)
    {
        if (request()->ajax()) {
            // Load relationships completely and eager load nested relationships
            $laporan->load([
                'fasilitasRuang.fasilitas',
                'fasilitasRuang.ruang',
                'pengguna'
            ]);

            // Format the response data
            $responseData = [
                'id_laporan' => $laporan->id_laporan,
                'deskripsi' => $laporan->deskripsi,
                // Handle both absolute URLs and relative paths
                'url_foto' => $laporan->url_foto,
                'status' => $laporan->status,
                'created_at' => $laporan->created_at,
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
                    ],
                ],
            ];

            return response()->json($responseData);
        }
        abort(404);
    }

    // Tampilkan form edit laporan
    public function edit(LaporanKerusakan $laporan)
    {
        return view('pages.laporan.edit', compact('laporan'));
    }

    // Update laporan
    public function update(Request $request, LaporanKerusakan $laporan)
    {
        $request->validate([
            'id_fas_ruang' => 'required|integer',
            'deskripsi' => 'required|string',
            'url_foto' => 'nullable|file|image|max:2048',
        ]);

        $data = $request->only(['id_fas_ruang', 'deskripsi']);

        try {
            if ($request->hasFile('url_foto')) {
                if ($laporan->url_foto && !filter_var($laporan->url_foto, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($laporan->url_foto);
                }
                $data['url_foto'] = $request->file('url_foto')->store('laporan_foto', 'public');
            }

            $laporan->update($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diupdate'
                ]);
            }

            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diupdate.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate laporan'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengupdate laporan.');
        }
    }

    // Hapus laporan
    public function destroy(LaporanKerusakan $laporan)
    {
        $laporan->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function getFasilitasByRuang($ruang_id)
    {
        $fasilitas = \App\Models\FasRuang::with('fasilitas')
            ->where('id_ruang', $ruang_id)
            ->get()
            ->groupBy('id_fasilitas')
            ->map(function($items) {
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
        $kode = \App\Models\FasRuang::where('id_ruang', $ruang_id)
            ->where('id_fasilitas', $fasilitas_id)
            ->get(['id_fas_ruang', 'kode_fasilitas']);
        return response()->json($kode);
    }

    public function getDetail(LaporanKerusakan $laporan)
    {
        $laporan->load(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang']);
        return response()->json($laporan);
    }
}
