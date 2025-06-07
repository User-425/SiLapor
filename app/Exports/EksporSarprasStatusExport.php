<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EksporSarprasStatusExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $laporans;
    protected $statusName;

    public function __construct($laporans, $statusName)
    {
        $this->laporans = $laporans;
        $this->statusName = $statusName;
    }

    public function array(): array
    {
        $data = [];
        $no = 1;

        foreach ($this->laporans as $laporan) {
            // Ambil umpan balik yang pertama/terbaru (jika ada)
            $umpanBalik = $laporan->umpanBaliks->first();

            $data[] = [
                'No' => $no++,
                'Nama Barang/Fasilitas' => $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? '-',
                'Nama Ruang' => $laporan->fasilitasRuang->ruang->nama_ruang ?? '-',
                'Gedung' => $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? '-',
                'Pelapor' => $laporan->pengguna->nama_lengkap ?? '-',
                'Deskripsi' => $laporan->deskripsi,
                'Status' => ucfirst(str_replace('_', ' ', $laporan->status)),
                'Rating Umpan Balik' => $umpanBalik ? $umpanBalik->rating : '-',
                'Komentar' => $umpanBalik ? $umpanBalik->komentar : '-',
                'Tanggal Pelaporan' => $laporan->created_at->format('d/m/Y H:i'),
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang/Fasilitas',
            'Nama Ruang',
            'Gedung',
            'Pelapor',
            'Deskripsi',
            'Status',
            'Rating Umpan Balik',
            'Komentar',
            'Tanggal Pelaporan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        // Bersihkan nama status untuk sheet name (max 31 karakter)
        return substr($this->statusName, 0, 31);
    }
}
