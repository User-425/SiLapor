<?php
namespace App\Http\Controllers;

use App\Models\FasRuang;
use App\Models\Fasilitas;
use App\Models\Ruang;
use Illuminate\Http\Request;

class FasRuangController extends Controller
{
    public function index()
    {
        $fasRuangs = FasRuang::with(['fasilitas', 'ruang'])->paginate(10);
        return view('pages.fasilitas.index', compact('fasRuangs'));
    }

    public function create()
    {
        $fasilitas = Fasilitas::all();
        $ruangs = Ruang::all();
        return view('pages.fasilitas.create', compact('fasilitas', 'ruangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'kode_fasilitas' => 'required|string|max:50|unique:fasilitas_ruang',
        ]);

        FasRuang::create($validated);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas ruang berhasil ditambahkan.');
    }

    public function edit(FasRuang $fasRuang)
    {
        $fasilitas = Fasilitas::all();
        $ruangs = Ruang::all();
        return view('pages.fasilitas.edit', compact('fasRuang', 'fasilitas', 'ruangs'));
    }

    public function update(Request $request, FasRuang $fasRuang)
    {
        $validated = $request->validate([
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'kode_fasilitas' => 'required|string|max:50|unique:fasilitas_ruang,kode_fasilitas,' . $fasRuang->id_fas_ruang . ',id_fas_ruang',
        ]);

        $fasRuang->update($validated);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas ruang berhasil diperbarui.');
    }

    public function destroy(FasRuang $fasRuang)
    {
        $fasRuang->delete();

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas ruang berhasil dihapus.');
    }
}