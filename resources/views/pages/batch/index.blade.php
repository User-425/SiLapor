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
    
    <!-- Batch Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nama Batch</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Progress</th>
                        <th class="py-3 px-6 text-center">Tanggal</th>
                        <th class="py-3 px-6 text-center">Laporan</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($batches as $batch)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left">
                                <span class="font-medium">{{ $batch->nama_batch }}</span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($batch->status == 'draft') bg-gray-100 text-gray-800
                                    @elseif($batch->status == 'aktif') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center justify-center">
                                    <div class="w-full max-w-xs">
                                        <div class="flex justify-between mb-1 text-xs">
                                            <span>{{ $batch->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $batch->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex flex-col">
                                    <span class="text-xs">
                                        <span class="font-medium">Mulai:</span> 
                                        {{ $batch->tanggal_mulai ? date('d/m/Y', strtotime($batch->tanggal_mulai)) : 'Belum dimulai' }}
                                    </span>
                                    <span class="text-xs mt-1">
                                        <span class="font-medium">Selesai:</span> 
                                        {{ $batch->tanggal_selesai ? date('d/m/Y', strtotime($batch->tanggal_selesai)) : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <span>{{ $batch->report_count_by_status['total'] }} total</span>
                                    <span class="text-xs text-green-600 mt-1">{{ $batch->report_count_by_status['selesai'] }} selesai</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('batches.show', $batch->id_batch) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded-md text-xs">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                Belum ada batch perbaikan. Buat batch baru untuk mulai mengelompokkan laporan kerusakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection