@extends('layouts.app')

@section('title', 'Riwayat Kerusakan Fasilitas')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Riwayat Kerusakan Fasilitas</h2>
        <div class="mt-2">
            <p><strong>Kode:</strong> {{ $fasRuang->kode_fasilitas }}</p>
            <p><strong>Fasilitas:</strong> {{ $fasRuang->fasilitas->nama_fasilitas }}</p>
            <p><strong>Ruang:</strong> {{ $fasRuang->ruang->nama_ruang }} - {{ $fasRuang->ruang->gedung->nama_gedung }}</p>
        </div>
    </div>

    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Kerusakan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm text-blue-600 font-semibold">Total Laporan</h4>
                <p class="text-2xl font-bold text-blue-800">{{ $stats['total'] }}</p>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm text-green-600 font-semibold">Laporan Selesai</h4>
                <p class="text-2xl font-bold text-green-800">{{ $stats['completed'] }}</p>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm text-purple-600 font-semibold">Rata-rata Waktu Perbaikan</h4>
                <p class="text-2xl font-bold text-purple-800">
                    {{ $stats['averageTimeToFix'] !== null ? $stats['averageTimeToFix'] . ' hari' : 'N/A' }}
                </p>
            </div>
            
            <div class="bg-yellow-50 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm text-yellow-600 font-semibold">Laporan Bulan Ini</h4>
                <p class="text-2xl font-bold text-yellow-800">{{ $stats['thisMonth'] }}</p>
            </div>
        </div>
        
        <div class="mt-6">
            <h4 class="text-sm text-gray-600 font-semibold mb-2">Status Laporan</h4>
            <div class="flex flex-wrap gap-3">
                @foreach($stats['statusCounts'] as $status => $count)
                    <div class="px-3 py-2 rounded-full 
                        {{ $status == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-800' : 
                           ($status == 'diproses' ? 'bg-blue-100 text-blue-800' : 
                           ($status == 'diperbaiki' ? 'bg-indigo-100 text-indigo-800' : 
                           ($status == 'selesai' ? 'bg-green-100 text-green-800' : 
                           ($status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                        {{ str_replace('_', ' ', ucwords($status)) }}: {{ $count }}
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- <div class="mt-6">
            <h4 class="text-sm text-gray-600 font-semibold mb-2">Tren Kerusakan 6 Bulan Terakhir</h4>
            <div style="height: 200px;">
                <canvas id="reportChart"></canvas>
            </div>
        </div> -->
        
        @if(count($stats['commonPhrases']) > 0)
        <div class="mt-6">
            <h4 class="text-sm text-gray-600 font-semibold mb-2">Pola Kerusakan Umum</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($stats['commonPhrases'] as $phrase => $count)
                    @if(strlen($phrase) > 3)
                    <span class="px-2 py-1 bg-gray-100 rounded-full text-sm">
                        {{ $phrase }} ({{ $count }})
                    </span>
                    @endif
                @endforeach
            </div>
            <p class="mt-2 text-sm text-gray-500">
                *Berdasarkan kata-kata yang sering muncul dalam deskripsi laporan
            </p>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto p-4 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Laporan</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Laporan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($laporanHistory as $laporan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $laporan->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $laporan->status == 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($laporan->status == 'diproses' ? 'bg-blue-100 text-blue-800' : 
                                   ($laporan->status == 'diperbaiki' ? 'bg-indigo-100 text-indigo-800' : 
                                   ($laporan->status == 'selesai' ? 'bg-green-100 text-green-800' : 
                                   ($laporan->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                                {{ str_replace('_', ' ', ucwords($laporan->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $laporan->pengguna->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($laporan->deskripsi, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('laporan.show', $laporan->id_laporan) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada riwayat kerusakan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $laporanHistory->links() }}
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        <a href="{{ route('fasilitas.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm flex items-center shadow-sm hover:bg-gray-700 w-max">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Fasilitas
        </a>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('reportChart').getContext('2d');
    const reportChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($stats['monthLabels']),
            datasets: [{
                label: 'Jumlah Laporan',
                data: @json($stats['monthlyData']),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection