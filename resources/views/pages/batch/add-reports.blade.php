@extends('layouts.app')

@section('title', 'Tambah Laporan ke Batch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Laporan ke Batch</h1>
        <a href="{{ route('batches.show', $batch->id_batch) }}" class="text-blue-600 hover:text-blue-800">
            &larr; Kembali ke detail batch
        </a>
    </div>
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">{{ $batch->nama_batch }}</h2>
            <p class="text-gray-600 text-sm">Status: {{ ucfirst($batch->status) }}</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('batches.add-reports', $batch->id_batch) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-800">Pilih Laporan Kerusakan</h2>
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <label for="selectAll" class="ml-2 text-sm text-gray-700">Pilih Semua</label>
                        </div>
                    </div>
                    
                    @if($availableReports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pilih</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($availableReports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_reports[]" value="{{ $report->id_laporan }}" 
                                                class="report-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->id_laporan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $report->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $report->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                                {{ \Illuminate\Support\Str::limit($report->deskripsi, 50) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($report->status == 'menunggu_verifikasi') bg-yellow-100 text-yellow-800
                                                @elseif($report->status == 'diproses') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->ranking ?: '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 p-6 text-center rounded-md">
                            <p class="text-gray-500">Tidak ada laporan yang tersedia untuk ditambahkan ke batch.</p>
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('batches.show', $batch->id_batch) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        Batal
                    </a>
                    @if($availableReports->count() > 0)
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Tambah ke Batch
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const reportCheckboxes = document.querySelectorAll('.report-checkbox');
        
        selectAllCheckbox.addEventListener('change', function() {
            reportCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
        
        reportCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // Check if all report checkboxes are checked
                    const allChecked = Array.from(reportCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
    });
</script>
@endpush
@endsection