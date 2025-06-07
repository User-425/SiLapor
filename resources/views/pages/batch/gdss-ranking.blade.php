@extends('layouts.app')

@section('title', 'Prioritas GDSS Batch')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Prioritas GDSS - {{ $batch->nama_batch }}</h1>
            <p class="text-gray-600">Menggunakan metode {{ ucfirst($gdssMethod) }} untuk menggabungkan penilaian pelapor dan sarpras</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('batches.gdss', ['batch' => $batch->id_batch, 'method' => ($gdssMethod == 'copeland' ? 'borda' : 'copeland')]) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200">
                Ganti ke Metode {{ ucfirst($gdssMethod == 'copeland' ? 'Borda' : 'Copeland') }}
            </a>
            <a href="{{ route('batches.gdss-calculations', ['batch' => $batch->id_batch, 'method' => $gdssMethod]) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Lihat Detail Perhitungan
            </a>
            <a href="{{ route('batches.show', $batch->id_batch) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Kembali ke Batch
            </a>
        </div>
    </div>
    
    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Prioritas laporan kerusakan dibawah ini telah diurutkan dengan metode GDSS {{ ucfirst($gdssMethod) }} yang menggabungkan penilaian dari pelapor dan sarpras. Anda dapat mengubah urutan prioritas sesuai kebutuhan dengan menarik (drag) baris tabel.
                </p>
                <p class="text-sm text-blue-700 mt-2">
                    <strong>{{ ucfirst($gdssMethod) }}</strong>: 
                    @if($gdssMethod == 'copeland')
                        Menggunakan perbandingan berpasangan untuk menentukan peringkat berdasarkan suara mayoritas.
                    @else
                        Memberikan poin berdasarkan peringkat, dengan peringkat lebih tinggi mendapat poin lebih banyak.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Rankings Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg text-gray-800">Hasil Penilaian GDSS {{ ucfirst($gdssMethod) }}</h2>
        </div>
        
        <form id="rankingForm" action="{{ route('batches.save-gdss-rankings', $batch->id_batch) }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank Pelapor</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank Sarpras</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor {{ ucfirst($gdssMethod) }}</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" class="bg-white divide-y divide-gray-200">
                        @foreach($rankedReports as $index => $report)
                        <tr class="hover:bg-gray-50 cursor-move" data-id="{{ $report->id_laporan }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="hidden" name="rankings[{{ $report->id_laporan }}]" value="{{ $index + 1 }}">
                                <span class="rank-number text-gray-900 font-medium">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->id_laporan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $report->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $report->fasilitasRuang->ruang->gedung->nama_gedung ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                    {{ \Illuminate\Support\Str::limit($report->deskripsi, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    #{{ $report->recommended_rank_pelapor }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    #{{ $report->recommended_rank_sarpras }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="font-medium {{ $report->final_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($report->final_score, is_integer($report->final_score) ? 0 : 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <a href="{{ route('batches.show', $batch->id_batch) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan Prioritas
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('sortable');
        var sortable = Sortable.create(el, {
            animation: 150,
            ghostClass: 'bg-blue-100',
            onEnd: function() {
                // Update rank numbers and hidden inputs
                const rows = document.querySelectorAll('#sortable tr');
                rows.forEach((row, index) => {
                    const rankNumber = index + 1;
                    row.querySelector('.rank-number').textContent = rankNumber;
                    const reportId = row.getAttribute('data-id');
                    row.querySelector('input[name="rankings[' + reportId + ']"]').value = rankNumber;
                });
            }
        });
    });
</script>
@endpush
@endsection