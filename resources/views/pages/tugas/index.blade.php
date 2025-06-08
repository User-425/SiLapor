@extends('layouts.app')

@section('title', 'Penugasan Teknisi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        

        <!-- Alert Success -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Enhanced Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-xl shadow-sm border border-amber-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-amber-800 mb-1">Belum Ditugaskan</h3>
                        <p class="text-3xl font-bold text-amber-900">{{ $totalBelumDitugaskan }}</p>
                        <p class="text-xs text-amber-600 mt-1">Laporan menunggu penugasan</p>
                    </div>
                    <div class="p-3 rounded-full bg-amber-100">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-blue-800 mb-1">Batch Aktif</h3>
                        <p class="text-3xl font-bold text-blue-900">{{ $batchesAktif }}</p>
                        <p class="text-xs text-blue-600 mt-1">Batch sedang diproses</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-green-800 mb-1">Dalam Pengerjaan</h3>
                        <p class="text-3xl font-bold text-green-900">{{ $dalamPengerjaan }}</p>
                        <p class="text-xs text-green-600 mt-1">Tugas sedang dikerjakan</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Batches -->
        @if($batches->count() > 0)
            @foreach($batches as $batch)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ $batch->nama_batch }}</h2>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 mr-2">Prioritas: {{ $batch->laporans->count() > 0 ? 'MABAC & GDSS' : 'Belum diatur' }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $batch->laporans->where('status', 'diproses')->count() }} laporan perlu ditugaskan
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-3">Progress: {{ $batch->progress_percentage }}%</span>
                            <div class="w-32 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-blue-600 rounded-full" style="width: {{ $batch->progress_percentage }}%"></div>
                            </div>
                            <a href="{{ route('batches.show', $batch->id_batch) }}" class="ml-4 text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>

                    @if($batch->laporans->where('status', 'diproses')->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fasilitas</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Prioritas</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($batch->laporans->where('status', 'diproses') as $laporan)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <span class="font-mono text-indigo-600">{{ $laporan->id_laporan }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div>
                                                    <div class="font-medium text-gray-900">
                                                        {{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                                                    </div>
                                                    <div class="flex items-center text-gray-500 text-xs">
                                                        <svg class="w-3 h-3 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        </svg>
                                                        {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }} - 
                                                        {{ $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="w-48">
                                                    <div class="truncate text-gray-700" title="{{ $laporan->deskripsi }}">
                                                        {{ Str::limit($laporan->deskripsi, 50) }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                <div class="font-medium">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }} WIB</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                @if(isset($laporan->ranking))
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($laporan->ranking <= 3)
                                                            bg-red-100 text-red-800 border border-red-200
                                                        @elseif($laporan->ranking <= 7)
                                                            bg-amber-100 text-amber-800 border border-amber-200
                                                        @else
                                                            bg-emerald-100 text-emerald-800 border border-emerald-200
                                                        @endif
                                                    ">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        #{{ $laporan->ranking }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">Belum di-ranking</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <button onclick="openDetailModal({{ $laporan->id_laporan }})" 
                                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Detail
                                                    </button>
                                                    <a href="{{ route('tugas.create', $laporan->id_laporan) }}" 
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-semibold rounded-lg text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:scale-105 shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Tugaskan
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-8 text-center">
                            <div class="max-w-md mx-auto">
                                <div class="mx-auto h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                                    <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Semua laporan telah ditugaskan</h3>
                                <p class="text-gray-500 text-sm">Tidak ada laporan yang perlu ditugaskan dalam batch ini.</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-16 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="mx-auto h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Batch Aktif</h3>
                        <p class="text-gray-500 text-sm mb-6">Saat ini tidak ada batch yang aktif untuk ditugaskan.</p>
                        <a href="{{ route('batches.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kelola Batch
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Laporan</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="mt-4" id="modalContent">
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(laporanId) {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Show loading
    modalContent.innerHTML = `
        <div class="animate-pulse">
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
        </div>
    `;
    
    // Get laporan details via AJAX
    fetch(`/api/laporan/${laporanId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                setTimeout(() => {
                    modalContent.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Laporan</label>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <span class="font-mono text-indigo-600">${data.id_laporan}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="font-medium text-blue-700">${data.batch?.nama_batch || 'N/A'}</div>
                                        <div class="text-xs text-gray-500 mt-1">Status: ${data.batch?.status ? data.batch.status.charAt(0).toUpperCase() + data.batch.status.slice(1) : 'N/A'}</div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="font-medium">${data.fasilitas_ruang?.fasilitas?.nama_fasilitas || 'N/A'}</div>
                                        <div class="text-sm text-gray-500 flex items-center mt-1">
                                            <svg class="w-3 h-3 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            ${data.fasilitas_ruang?.ruang?.nama_ruang || 'N/A'} - ${data.fasilitas_ruang?.ruang?.gedung?.nama_gedung || 'N/A'}
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pelaporan</label>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="font-medium">${new Date(data.created_at).toLocaleDateString('id-ID')}</div>
                                        <div class="text-sm text-gray-500">${new Date(data.created_at).toLocaleTimeString('id-ID')} WIB</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kerusakan</label>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-gray-700 leading-relaxed">${data.deskripsi || 'Tidak ada deskripsi'}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        ${data.ranking ? `
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                ${data.ranking <= 3 ? 'bg-red-100 text-red-800' : 
                                                  data.ranking <= 7 ? 'bg-amber-100 text-amber-800' : 
                                                  'bg-emerald-100 text-emerald-800'}">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                Prioritas #${data.ranking}
                                            </span>
                                        ` : '<span class="text-gray-400">Belum di-ranking</span>'}
                                    </div>
                                </div>
                                
                                ${data.foto_kerusakan ? `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Kerusakan</label>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <img src="${data.foto_kerusakan}" alt="Foto Kerusakan" class="w-full h-48 object-cover rounded-lg">
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button onclick="closeDetailModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Tutup
                            </button>
                            <a href="/tugas/create/${data.id_laporan}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                                Tugaskan Teknisi
                            </a>
                        </div>
                    `;
                }, 300);
            }
        })
        .catch(error => {
            modalContent.innerHTML = `
                <div class="bg-red-50 p-4 rounded-md">
                    <p class="text-red-700">Gagal memuat data: ${error.message}</p>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Tutup
                    </button>
                </div>
            `;
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>
@endsection