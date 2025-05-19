<?php
namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GedungController extends Controller
{
    /**
     * Tampilkan daftar gedung (dengan pencarian opsional)
     */
    public function index(Request $request)
    {
        $query = Gedung::query();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('nama_gedung', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('deskripsi_lokasi', 'LIKE', "%{$searchTerm}%");
        }

        $gedungs = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.gedung.index', compact('gedungs'));
    }

    /**
     * Form tambah gedung
     */
    public function create()
    {
        return view('pages.gedung.create');
    }

    /**
     * Simpan gedung baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:100',
            'deskripsi_lokasi' => 'nullable|string',
        ]);

        Gedung::create([
            'nama_gedung' => $request->nama_gedung,
            'deskripsi_lokasi' => $request->deskripsi_lokasi,
        ]);

        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil ditambahkan!');
    }

    /**
     * Form edit gedung
     */
    public function edit(Gedung $gedung)
    {
        return view('pages.gedung.edit', compact('gedung'));
    }

    /**
     * Perbarui data gedung
     */
    public function update(Request $request, Gedung $gedung)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:100',
            'deskripsi_lokasi' => 'nullable|string',
        ]);

        $gedung->update([
            'nama_gedung' => $request->nama_gedung,
            'deskripsi_lokasi' => $request->deskripsi_lokasi,
        ]);

        return redirect()->route('gedung.index')->with('success', 'Data gedung berhasil diperbarui!');
    }

    /**
     * Hapus gedung
     */
    public function destroy(Gedung $gedung)
    {
        $gedung->delete();
        return redirect()->route('gedung.index')->with('success', 'Gedung berhasil dihapus!');
    }
}
