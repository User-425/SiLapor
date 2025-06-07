@extends('layouts.app')

@section('title', 'Prioritas Laporan Batch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Prioritas Laporan - {{ $batch->nama_batch }}</h1>
            <p class="text-gray-600">Menentukan prioritas menggunakan metode MABAC</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('batches.calculations', $batch->id_batch) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Lihat Detail Perhitungan
            </a>
            <a href="{{ route('batches.show', $batch->id_batch) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Kembali
            </a>
        </div>
    </div>
    
    <!-- Alert Messages -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Prioritas laporan kerusakan dibawah ini telah diurutkan menggunakan metode MABAC berdasarkan kriteria tingkat kerusakan, dampak akademik, dan kebutuhan pengguna. Anda dapat mengubah urutan prioritas sesuai kebutuhan dengan menarik (drag) baris tabel.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Priority Ranking Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Urutan Prioritas Laporan</h2>
        </div>
        
        <form action="{{ route('batches.save-rankings', $batch->id_batch) }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Kerusakan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dampak Akademik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kebutuhan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MABAC Score</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" class="bg-white divide-y divide-gray-200">
                        @foreach($rankedReports as $index => $report)
                        <tr data-id="{{ $report->id_laporan }}" class="hover:bg-gray-50 cursor-move">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">{{ $index + 1 }}</span>
                                    <input type="hidden" name="rankings[{{ $report->id_laporan }}]" value="{{ $index + 1 }}">
                                    <svg class="ml-2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                    </svg>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->id_laporan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}
                                <div class="text-xs text-gray-500">
                                    {{ $report->fasilitasRuang->ruang->gedung->nama_gedung ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                    {{ \Illuminate\Support\Str::limit($report->deskripsi, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->kriteria->tingkat_kerusakan_sarpras ?? '3' }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->kriteria->dampak_akademik_sarpras ?? '3' }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->kriteria->kebutuhan_sarpras ?? '3' }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-medium {{ $report->mabac_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($report->mabac_score, 4) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan Urutan Prioritas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sortable table
        const sortableTable = document.getElementById('sortable');
        if (sortableTable) {
            new Sortable(sortableTable, {
                animation: 150,
                ghostClass: 'bg-gray-100',
                onEnd: function() {
                    // Update priority numbers and hidden inputs after sorting
                    const rows = sortableTable.querySelectorAll('tr');
                    rows.forEach((row, index) => {
                        const priorityCell = row.querySelector('td:first-child span');
                        const hiddenInput = row.querySelector('input[type="hidden"]');
                        if (priorityCell) priorityCell.textContent = index + 1;
                        if (hiddenInput) hiddenInput.value = index + 1;
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection