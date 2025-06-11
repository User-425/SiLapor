@extends('layouts.app')

@section('title', 'Riwayat Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800">Daftar Riwayat</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 text-left">
                    <tr>
                        <th class="px-4 py-3">Fasilitas</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Tgl. Penugasan</th>
                        <th class="px-4 py-3">Tgl. Selesai</th>
                        <th class="px-4 py-3">Catatan</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $item)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $item->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $item->laporan->fasilitasRuang->ruang->nama_ruang }} - 
                                {{ $item->laporan->fasilitasRuang->ruang->gedung->nama_gedung }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $item->catatan ?: '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Tidak ada riwayat perbaikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
