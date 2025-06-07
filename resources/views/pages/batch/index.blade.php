@extends('layouts.app')

@section('title', 'Manajemen Batch Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Batch Perbaikan</h1>
        <a href="{{ route('batches.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Batch Baru
        </a>
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

    <!-- Unbatched Reports Card -->
    @if($unbatchedReportsCount > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Terdapat {{ $unbatchedReportsCount }} laporan yang belum masuk batch.
                    <a href="{{ route('batches.create') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                        Buat batch baru
                    </a>
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Batch List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($batches as $batch)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $batch->nama_batch }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($batch->status == 'draft') bg-gray-100 text-gray-800
                            @elseif($batch->status == 'aktif') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif
                        ">
                            {{ ucfirst($batch->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 text-sm">
                            <span>Progress</span>
                            <span>{{ $batch->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $batch->progress_percentage }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4 grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-600">Mulai:</span>
                            <span>{{ $batch->tanggal_mulai ? date('d/m/Y', strtotime($batch->tanggal_mulai)) : 'Belum dimulai' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Selesai:</span>
                            <span>{{ $batch->tanggal_selesai ? date('d/m/Y', strtotime($batch->tanggal_selesai)) : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total:</span>
                            <span>{{ $batch->report_count_by_status['total'] }} laporan</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Selesai:</span>
                            <span>{{ $batch->report_count_by_status['selesai'] }} laporan</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-center">
                        <a href="{{ route('batches.show', $batch->id_batch) }}" class="text-blue-600 hover:text-blue-800 font-medium">Lihat Detail Batch</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-500">Belum ada batch perbaikan. Buat batch baru untuk mulai mengelompokkan laporan kerusakan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection