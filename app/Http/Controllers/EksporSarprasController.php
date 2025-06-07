<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\LaporanKerusakan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EksporSarprasPerPeriodeExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EksporSarprasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('peran:sarpras');
    }

    public function form()
    {
        $periodes = Periode::orderBy('tanggal_mulai', 'desc')->get();
        return view('pages.laporan.eksporlaporan', compact('periodes'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode,id',
            'format' => 'required|in:excel,pdf'
        ]);

        $periode = Periode::findOrFail($request->periode_id);
        $tanggalMulai = Carbon::parse($periode->tanggal_mulai)->startOfDay();
        $tanggalSelesai = Carbon::parse($periode->tanggal_selesai)->endOfDay();

        $filename = 'laporan_sarpras_' . str_replace(' ', '_', $periode->nama_periode) . '_' . now()->format('Ymd_His');

        if ($request->format === 'excel') {
            try {
                return Excel::download(
                    new EksporSarprasPerPeriodeExport($periode),
                    $filename . '.xlsx'
                );
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error saat export: ' . $e->getMessage());
            }
        } elseif ($request->format === 'pdf') {
            try {
                $laporans = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna', 'umpanBaliks'])
                    ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                    ->get();

                $pdf = Pdf::loadView('pages.laporan.pdfview', compact('laporans', 'periode'));
                return $pdf->download($filename . '.pdf');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error saat export PDF: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Format tidak valid');
    }
}
