@extends('layouts.app')

@section('title', 'Riwayat Perbaikan')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Riwayat Perbaikan</h1>
        <div class="text-sm text-gray-600">
            Total: <span class="font-semibold text-blue-600">{{ $riwayat->count() }}</span> perbaikan
        </div>
    </div>

    <div class="overflow-x-auto border rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Penugasan</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Selesai</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($riwayat as $item)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $item->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->laporan->fasilitasRuang->kode_fasilitas }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $item->laporan->fasilitasRuang->ruang->nama_ruang }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->laporan->fasilitasRuang->ruang->gedung->nama_gedung }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $item->catatan ?: '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Selesai
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data riwayat</h3>
                            <p class="text-gray-500">Belum ada perbaikan yang diselesaikan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


@endsection
