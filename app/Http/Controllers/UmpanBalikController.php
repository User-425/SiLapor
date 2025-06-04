<?php
namespace App\Http\Controllers;

use App\Models\UmpanBalik;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmpanBalikController extends Controller
{
    public function create($id_laporan)
    {
        $laporan = LaporanKerusakan::findOrFail($id_laporan);
        if ($laporan->status !== 'selesai' || $laporan->id_pengguna !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('pages.umpan_balik.create', compact('laporan')); // Updated path
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required|exists:laporan_kerusakan,id_laporan',
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'rating' => 'required|integer|between:1,5',
            'komentar' => 'nullable|string|max:500',
        ]);

        UmpanBalik::create($request->all());

        return redirect()->route('laporan.riwayat')
            ->with('success', 'Umpan balik berhasil dikirim!');
    }

    public function index()
    {
        if (Auth::user()->peran !== 'sarpras') {
            abort(403, 'Unauthorized');
        }

        $umpanBaliks = UmpanBalik::with(['laporan', 'pengguna'])->paginate(10);
        return view('pages.umpan_balik.index', compact('umpanBaliks'));
    }
}