@extends('layouts.app')

@section('title', 'Detail Batch Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header with actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $batch->nama_batch }}</h1>
            <p class="text-gray-600">Dibuat pada {{ $batch->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('batches.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Kembali
            </a>
            
            @if($batch->status === 'draft')
            <form action="{{ route('batches.activate', $batch->id_batch) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Aktifkan Batch
                </button>
            </form>
            @elseif($batch->status === 'aktif')
            <form action="{{ route('batches.complete', $batch->id_batch) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan batch ini?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Selesaikan Batch
                </button>
            </form>
            @endif
        </div>
    </div>
    
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <!-- Batch Info Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Informasi Batch</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="px-3 py-1 inline-flex text-sm rounded-full 
                        @if($batch->status == 'draft') bg-gray-100 text-gray-800
                        @elseif($batch->status == 'aktif') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($batch->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Mulai</p>
                    <p class="font-medium">{{ $batch->tanggal_mulai ? date('d/m/Y', strtotime($batch->tanggal_mulai)) : 'Belum dimulai' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Target Selesai</p>
                    <p class="font-medium">{{ $batch->tanggal_selesai ? date('d/m/Y', strtotime($batch->tanggal_selesai)) : 'Belum ditentukan' }}</p>
                </div>
            </div>
            
            @if($batch->catatan)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Catatan</p>
                <p>{{ $batch->catatan }}</p>
            </div>
            @endif
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between mb-1 text-sm">
                    <span>Progress</span>
                    <span>{{ $batch->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $batch->progress_percentage }}%"></div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mt-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $batch->report_count_by_status['total'] }}</div>
                        <div class="text-xs text-gray-500">Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $batch->report_count_by_status['menunggu_verifikasi'] }}</div>
                        <div class="text-xs text-gray-500">Menunggu</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $batch->report_count_by_status['diproses'] }}</div>
                        <div class="text-xs text-gray-500">Diproses</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $batch->report_count_by_status['diperbaiki'] }}</div>
                        <div class="text-xs text-gray-500">Diperbaiki</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $batch->report_count_by_status['selesai'] }}</div>
                        <div class="text-xs text-gray-500">Selesai</div>
                    </div>
                </div>
            </div>
            
            <!-- Add this inside the batch info card for active batches -->
            @if($batch->status === 'aktif')
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('tugas.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Penugasan Teknisi
                </a>
                <p class="text-sm text-gray-500 mt-2">Tugaskan teknisi untuk mengerjakan laporan dari batch ini.</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Batch Actions -->
    @if($batch->status !== 'selesai')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Laporan dalam Batch</h2>
        <div class="flex space-x-2">
            @if($availableReports > 0)
            <a href="{{ route('batches.add-reports', $batch->id_batch) }}" class="px-4 py-2 flex items-center text-sm bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Laporan ({{ $availableReports }})
            </a>
            @endif
            
            @if($batch->status === 'aktif')
            <a href="{{ route('batches.gdss', $batch->id_batch) }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-md hover:bg-indigo-100">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                </svg>
                Sortir Prioritas
            </a>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Laporan dalam Batch -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                        @if($batch->status !== 'selesai')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($batch->laporans as $laporan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $laporan->id_laporan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}
                            <div class="text-xs text-gray-500">
                                {{ $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="truncate max-w-xs" title="{{ $laporan->deskripsi }}">
                                {{ \Illuminate\Support\Str::limit($laporan->deskripsi, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($laporan->status == 'menunggu_verifikasi') bg-yellow-100 text-yellow-800
                                @elseif($laporan->status == 'diproses') bg-blue-100 text-blue-800
                                @elseif($laporan->status == 'diperbaiki') bg-indigo-100 text-indigo-800
                                @elseif($laporan->status == 'selesai') bg-green-100 text-green-800
                                @elseif($laporan->status == 'ditolak') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $laporan->ranking ?: '-' }}
                        </td>
                        @if($batch->status !== 'selesai')
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('laporan.show', $laporan->id_laporan) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                                <form action="{{ route('batches.remove-report', ['batch' => $batch->id_batch, 'laporan' => $laporan->id_laporan]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin mengeluarkan laporan ini dari batch?')">
                                        Keluarkan
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $batch->status !== 'selesai' ? '7' : '6' }}" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada laporan dalam batch ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection