<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::paginate(10);
        return view('pages.tipe_fasilitas.index', compact('fasilitas'));
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

        return view('pages.tipe_fasilitas.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        Fasilitas::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/tipe_fasilitas')->with('success', 'Data fasilitas berhasil ditambahkan');
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
        return view('pages.tipe_fasilitas.edit', compact('breadcrumb', 'page', 'activeMenu', 'fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        $fasilitas = Fasilitas::findOrFail($id);

        $fasilitas->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/tipe_fasilitas')->with('success', 'Data fasilitas berhasil diubah');
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::find($id);

        if (!$fasilitas) {
            return redirect('/tipe_fasilitas')->with('error', 'Data fasilitas tidak ditemukan');
        }

        try {
            $fasilitas->delete();
            return redirect('/tipe_fasilitas')->with('success', 'Data fasilitas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/tipe_fasilitas')->with('error', 'Gagal menghapus data fasilitas karena masih terkait dengan data lain');
        }
    }
}