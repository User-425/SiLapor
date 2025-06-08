<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kerusakan - {{ $periode->nama_periode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .periode-info {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .status-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .status-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KERUSAKAN SARANA DAN PRASARANA</h1>
        <h2>Periode: {{ $periode->nama_periode }}</h2>
    </div>

    <div class="periode-info">
        <strong>Informasi Periode:</strong><br>
        Nama Periode: {{ $periode->nama_periode }}<br>
        Tanggal Mulai: {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d F Y') }}<br>
        Tanggal Selesai: {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d F Y') }}<br>
        Total Laporan: {{ $laporans->count() }}
    </div>

    @if($laporans->count() > 0)
        @php
        $statusLabels = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diverifikasi' => 'Diverifikasi',
            'ditugaskan' => 'Ditugaskan',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];
        $laporanByStatus = $laporans->groupBy('status');
        @endphp

        @foreach($statusLabels as $statusKey => $statusLabel)
            @if(isset($laporanByStatus[$statusKey]) && $laporanByStatus[$statusKey]->count() > 0)
            <div class="status-section">
                <div class="status-header">
                    {{ $statusLabel }} ({{ $laporanByStatus[$statusKey]->count() }} laporan)
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 20%">Fasilitas</th>
                            <th style="width: 12%">Ruang</th>
                            <th style="width: 12%">Gedung</th>
                            <th style="width: 15%">Pelapor</th>
                            <th style="width: 25%">Deskripsi</th>
                            <th style="width: 11%">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanByStatus[$statusKey] as $index => $laporan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? '-' }}</td>
                            <td>{{ $laporan->fasilitasRuang->ruang->nama_ruang ?? '-' }}</td>
                            <td>{{ $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? '-' }}</td>
                            <td>{{ $laporan->pengguna->nama_lengkap ?? '-' }}</td>
                            <td>{{ Str::limit($laporan->deskripsi, 100) }}</td>
                            <td>{{ $laporan->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        @endforeach
    @else
    <div style="text-align: center; padding: 50px; color: #666;">
        <h3>Tidak ada data laporan untuk periode ini</h3>
    </div>
    @endif

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
