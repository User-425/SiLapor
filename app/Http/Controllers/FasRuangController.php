<?php

namespace App\Http\Controllers;

use App\Models\FasRuang;
use App\Models\Fasilitas;
use App\Models\Ruang;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        try {
            if ($fasRuang->laporanKerusakan()->count() > 0) {
                return redirect()->route('fasilitas.index')
                    ->with('error', 'Tidak dapat menghapus fasilitas ruang karena masih memiliki laporan kerusakan terkait');
            }

            $fasRuang->delete();
            return redirect()->route('fasilitas.index')
                ->with('success', 'Fasilitas ruang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Gagal menghapus fasilitas ruang: ' . $e->getMessage());
        }
    }

    public function generateQR($id)
    {
        try {
            $fasRuang = FasRuang::with(['fasilitas', 'ruang.gedung'])->findOrFail($id);

            $baseUrl = config('app.url');
            $code = base64_encode($id);
        
            $development_host = "http://192.168.31.38:8000";
            $url = rtrim($development_host, '/') . '/laporan/quick/' . $code;
            // $url = route('laporan.quick', ['code' => base64_encode($id)]);
            $qrcode = QrCode::size(300)
                ->margin(2)
                ->generate($url);

            return view('pages.fasilitas.qr', [ 
                'fasRuang' => $fasRuang,
                'qrcode' => $qrcode,
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Gagal generate QR Code');
        }
    }
}
