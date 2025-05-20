<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Ruang;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::with('ruang')->paginate(10); // include relasi ruang
        return view('pages.fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Fasilitas',
            'list' => ['Home', 'Master', 'Fasilitas']
        ];

        $page = (object) [
            'title' => 'Form Tambah Fasilitas'
        ];

        $activeMenu = 'fasilitas';

        $ruangs = Ruang::all(); // ambil data ruang untuk dropdown
        return view('pages.fasilitas.create', compact('breadcrumb', 'page', 'activeMenu', 'ruangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ruang' => 'required|integer',
            'nama_fasilitas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        Fasilitas::create([
            'id_ruang' => $request->id_ruang,
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/fasilitas')->with('success', 'Data fasilitas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Fasilitas',
            'list' => ['Home', 'Master', 'Edit Fasilitas']
        ];

        $page = (object) [
            'title' => 'Form Edit Fasilitas'
        ];

        $activeMenu = 'fasilitas';

        $fasilitas = Fasilitas::findOrFail($id);
        $ruangs = Ruang::all(); // ambil data ruang
        return view('pages.fasilitas.edit', compact('breadcrumb', 'page', 'activeMenu', 'fasilitas', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_ruang' => 'required|integer',
            'nama_fasilitas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        $fasilitas = Fasilitas::findOrFail($id);

        $fasilitas->update([
            'id_ruang' => $request->id_ruang,
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/fasilitas')->with('success', 'Data fasilitas berhasil diubah');
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return redirect('/fasilitas')->with('error', 'Data fasilitas tidak ditemukan');
        }

        try {
            $fasilitas->delete();
            return redirect('/fasilitas')->with('success', 'Data fasilitas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/fasilitas')->with('error', 'Gagal menghapus data fasilitas karena masih terkait dengan data lain');
        }
    }
}