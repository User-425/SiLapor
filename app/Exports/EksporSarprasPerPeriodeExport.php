<?php

namespace App\Exports;

use App\Models\LaporanKerusakan;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class EksporSarprasPerPeriodeExport implements WithMultipleSheets
{
    use Exportable;

    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        $sheets = [];
        $tanggalMulai = Carbon::parse($this->periode->tanggal_mulai)->startOfDay();
        $tanggalSelesai = Carbon::parse($this->periode->tanggal_selesai)->endOfDay();

        // Daftar status yang akan dijadikan tab
        $statusList = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diverifikasi' => 'Diverifikasi',
            'ditugaskan' => 'Ditugaskan',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];

        foreach ($statusList as $statusKey => $statusName) {
            $laporans = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna', 'umpanBaliks'])
                ->where('status', $statusKey)
                ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                ->get();

            // Hanya buat sheet jika ada data laporan dengan status tersebut
            if ($laporans->count() > 0) {
                $sheets[] = new EksporSarprasStatusExport($laporans, $statusName);
            }
        }

        // Jika tidak ada sheet, buat sheet dengan semua data
        if (empty($sheets)) {
            $allLaporans = LaporanKerusakan::with(['fasilitasRuang.fasilitas', 'fasilitasRuang.ruang.gedung', 'pengguna', 'umpanBaliks'])
                ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                ->get();

            if ($allLaporans->count() > 0) {
                $sheets[] = new EksporSarprasStatusExport($allLaporans, 'Semua Laporan');
            } else {
                // Buat sheet kosong jika benar-benar tidak ada data
                $emptyData = collect();
                $sheets[] = new EksporSarprasStatusExport($emptyData, 'Tidak Ada Data');
            }
        }

        return $sheets;
    }
}
