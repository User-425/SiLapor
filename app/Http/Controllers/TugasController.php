<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\LaporanKerusakan;
use App\Models\Pengguna;

class TugasController extends Controller
{
    // Menampilkan semua tugas (untuk admin/sarpras)
    public function index()
{
    // Laporan yang belum ditugaskan
    $laporanBelumDitugaskan = \App\Models\LaporanKerusakan::whereDoesntHave('tugas')->get();

    return view('pages.tugas.index', compact('laporanBelumDitugaskan'));
}


    // Menampilkan form untuk menugaskan teknisi
    public function create($id_laporan)
    {
        $laporan = LaporanKerusakan::findOrFail($id_laporan);
        $teknisi = Pengguna::where('peran', 'teknisi')->get(); // hanya pengguna dengan peran teknisi

        return view('pages.tugas.create', compact('laporan', 'teknisi'));
    }

    // Menyimpan tugas baru
    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required|exists:laporan_kerusakan,id_laporan',
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'batas_waktu' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        Tugas::create([
            'id_laporan' => $request->id_laporan,
            'id_pengguna' => $request->id_pengguna,
            'prioritas' => $request->prioritas,
            'batas_waktu' => $request->batas_waktu,
            'catatan' => $request->catatan,
            'status' => 'ditugaskan'
        ]);

        return redirect()->route('pages.laporan.index')->with('success', 'Tugas berhasil ditambahkan!');
    }
}
