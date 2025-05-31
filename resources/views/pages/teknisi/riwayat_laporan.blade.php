@extends('layouts.app')

@section('title', 'Riwayat Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Riwayat Perbaikan Teknisi</h1>
        <p class="text-gray-600 mt-1">Daftar riwayat perbaikan yang telah diselesaikan</p>
    </div>

    <!-- Riwayat List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800">Daftar Riwayat</h2>
        </div>

        <div class="divide-y">
            @forelse($riwayat as $item)
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex justify-between items-start">
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">
                                    {{ $item->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                                </h3>
                                <p class="text-gray-600">
                                    {{ $item->laporan->fasilitasRuang->ruang->nama_ruang }} - 
                                    {{ $item->laporan->fasilitasRuang->ruang->gedung->nama_gedung }}
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Penugasan</p>
                                    <p class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Selesai</p>
                                    <p class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Catatan Perbaikan</p>
                                <p class="mt-1 text-gray-800">{{ $item->catatan_perbaikan ?: '-' }}</p>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                            Selesai
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Tidak ada riwayat perbaikan.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection