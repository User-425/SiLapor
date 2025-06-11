@extends('layouts.app')

@section('title', 'Dashboard Teknisi')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Tugas Ditugaskan Card -->
    <a href="{{ route('teknisi.index') }}" class="block bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Tugas Ditugaskan</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ $ditugaskan }}</p>
            </div>
        </div>
    </a>

    <!-- Sedang Dikerjakan Card -->
    <a href="{{ route('teknisi.index') }}" class="block bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Sedang Dikerjakan</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ $dikerjakan }}</p>
            </div>
        </div>
    </a>

    <!-- Selesai Card -->
    <a href="{{ route('teknisi.riwayat') }}" class="block bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Selesai</h2>
                <p class="text-2xl font-semibold text-gray-800">{{ $selesai }}</p>
            </div>
        </div>
    </a>
</div>

<!-- Tugas Aktif Section -->
<div class="mt-8">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b px-6 py-4">
            <h3 class="text-lg font-medium text-gray-800">Tugas Aktif</h3>
        </div>
        <div class="p-6">
            @if($tugasAktif->isEmpty())
                <p class="text-gray-500">Tidak ada tugas aktif.</p>
            @else
                <div class="space-y-4">
                    @foreach($tugasAktif as $tugas)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-800">
                                        {{ $tugas->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                                    </h4>
                                    <p class="text-gray-600">
                                        {{ $tugas->laporan->fasilitasRuang->ruang->nama_ruang }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <span class="px-3 py-1 rounded-full text-sm
                                        @if($tugas->status === 'ditugaskan') bg-blue-100 text-blue-800
                                        @elseif($tugas->status === 'dikerjakan') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($tugas->status) }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-sm
                                        @if($tugas->prioritas === 'tinggi') bg-red-100 text-red-800
                                        @elseif($tugas->prioritas === 'sedang') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($tugas->prioritas) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection