@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Maintenance Fasilitas</h1>
        <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informasi Fasilitas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p><span class="font-semibold">Kode:</span> {{ $fasRuang->kode_fasilitas }}</p>
                <p><span class="font-semibold">Nama:</span> {{ $fasRuang->fasilitas->nama_fasilitas }}</p>
                <p><span class="font-semibold">Ruang:</span> {{ $fasRuang->ruang->nama_ruang }}</p>
                <p><span class="font-semibold">Gedung:</span> {{ $fasRuang->ruang->gedung->nama_gedung }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Laporan Aktif</h2>
        @if($activeReports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activeReports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->id_laporan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->pengguna->nama_lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $report->status == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $report->status == 'diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $report->status == 'diperbaiki' ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                    {{ str_replace('_', ' ', ucwords($report->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('laporan.show', $report->id_laporan) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Tidak ada laporan aktif saat ini.</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Riwayat Maintenance Terbaru</h2>
        @if($completedReports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($completedReports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->id_laporan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->pengguna->nama_lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('laporan.show', $report->id_laporan) }}" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="{{ route('fasilitas.history', $fasRuang->id_fas_ruang) }}" class="text-indigo-600 hover:text-indigo-800">
                    Lihat semua riwayat
                </a>
            </div>
        @else
            <p class="text-gray-500">Belum ada riwayat maintenance.</p>
        @endif
    </div>
</div>
@endsection