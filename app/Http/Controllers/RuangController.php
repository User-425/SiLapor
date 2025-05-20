<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use App\Models\Gedung;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index()
    {
    $ruangs = Ruang::with('gedung')->paginate(10); // include relasi gedung

    return view('pages.ruang.index', compact('ruangs'));
    }

    public function create()
    {
    $breadcrumb = (object) [
        'title' => 'Tambah Ruang',
        'list' => ['Home', 'Master', 'Ruang']
    ];

    $page = (object) [
        'title' => 'Form Tambah Ruang'
    ];

    $activeMenu = 'ruang';

    $gedungs = Gedung::all(); // <-- ambil data gedung untuk dropdown

    return view('pages.ruang.create', compact('breadcrumb', 'page', 'activeMenu', 'gedungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gedung' => 'required|integer',
            'nama_ruang' => 'required|string|max:100',
            'deskripsi_lokasi' => 'nullable|string'
        ]);

        Ruang::create([
            'id_gedung' => $request->id_gedung,
            'nama_ruang' => $request->nama_ruang,
            'deskripsi_lokasi' => $request->deskripsi_lokasi
        ]);

        return redirect('/ruang')->with('success', 'Data ruang berhasil ditambahkan');
    }

    public function edit($id)
    {
    $breadcrumb = (object) [
        'title' => 'Edit Ruang',
        'list' => ['Home', 'Master', 'Edit Ruang']
    ];

    $page = (object) [
        'title' => 'Form Edit Ruang'
    ];

    $activeMenu = 'ruang';

    $ruang = Ruang::findOrFail($id);
    $gedungs = Gedung::all(); // <-- ambil data gedung

    return view('pages.ruang.edit', compact('breadcrumb', 'page', 'activeMenu', 'ruang', 'gedungs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_gedung' => 'required|integer',
            'nama_ruang' => 'required|string|max:100',
            'deskripsi_lokasi' => 'nullable|string'
        ]);

        $ruang = Ruang::findOrFail($id);

        $ruang->update([
            'id_gedung' => $request->id_gedung,
            'nama_ruang' => $request->nama_ruang,
            'deskripsi_lokasi' => $request->deskripsi_lokasi
        ]);

        return redirect('/ruang')->with('success', 'Data ruang berhasil diubah');
    }

    public function destroy($id)
    {
        $ruang = Ruang::find($id);

        if (!$ruang) {
            return redirect('/ruang')->with('error', 'Data ruang tidak ditemukan');
        }

        try {
            $ruang->delete();
            return redirect('/ruang')->with('success', 'Data ruang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/ruang')->with('error', 'Gagal menghapus data ruang karena masih terkait dengan data lain');
        }
    }
}
