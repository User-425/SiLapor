@extends('layouts.app')

@section('title', 'Dashboard Sarana Prasarana')

@section('content')

<!-- Header -->


    <!-- Quick Action Buttons -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('laporan.index', ['status' => 'menunggu_verifikasi']) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Verifikasi Laporan ({{ $laporanStats['menunggu_verifikasi'] }})
            </a>
            <a href="{{ route('tugas.index') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Kelola Tugas
            </a>
            <a href="{{ route('laporan.export') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Data
            </a>
        </div>
    </div>

    <!-- Status Overview Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- Total Reports -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center border-l-4 border-blue-500">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Laporan</p>
                <p class="text-xl font-bold">{{ $laporanStats['total'] }}</p>
            </div>
        </div>

        <!-- Waiting Verification -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center border-l-4 border-yellow-500">
            <div class="rounded-full bg-yellow-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
                <p class="text-xl font-bold">{{ $laporanStats['menunggu_verifikasi'] }}</p>
            </div>
        </div>

        <!-- Processing -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center border-l-4 border-indigo-500">
            <div class="rounded-full bg-indigo-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Diproses</p>
                <p class="text-xl font-bold">{{ $laporanStats['diproses'] }}</p>
            </div>
        </div>

        <!-- Being Repaired -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center border-l-4 border-blue-700">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Diperbaiki</p>
                <p class="text-xl font-bold">{{ $laporanStats['diperbaiki'] }}</p>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center border-l-4 border-green-500">
            <div class="rounded-full bg-green-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Selesai</p>
                <p class="text-xl font-bold">{{ $laporanStats['selesai'] }}</p>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-lg shadow p-4 flex items-center border-l-4 border-red-500">
            <div class="rounded-full bg-red-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Ditolak</p>
                <p class="text-xl font-bold">{{ $laporanStats['ditolak'] }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2">
            
            <!-- Waiting Verification Reports -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b px-4 py-3 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Menunggu Verifikasi</h2>
                    <a href="{{ route('laporan.index', ['status' => 'menunggu_verifikasi']) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pendingReports as $report)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $report->fasilitasRuang->fasilitas->nama_fasilitas }}
                                    </div>
                                    <div class="text-xs text-gray-500">ID: {{ $report->fasilitasRuang->kode_fasilitas }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $report->fasilitasRuang->ruang->nama_ruang }}</div>
                                    <div class="text-xs text-gray-500">{{ $report->fasilitasRuang->ruang->gedung->nama_gedung }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $report->pengguna->nama_pengguna }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $report->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $report->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('laporan.show', $report) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">
                                    Tidak ada laporan yang menunggu verifikasi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Analytics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Reports Trend -->
                <!-- <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold mb-4">Tren Laporan (7 Hari Terakhir)</h2>
                    <div class="h-64">
                        <canvas id="reportsChart"></canvas>
                    </div>
                </div> -->

                <!-- Reports by Location -->
                <!-- <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-lg font-semibold mb-4">Laporan per Gedung</h2>
                    <div class="h-64">
                        <canvas id="locationChart"></canvas>
                    </div>
                </div> -->
                <!-- Recent Tasks -->
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b px-4 py-3 flex justify-between items-center">
                        <h2 class="text-lg font-semibold">Tugas Terbaru</h2>
                        <a href="{{ route('tugas.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Lihat Semua
                        </a>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @forelse($recentTasks as $task)
                        <li class="p-4">
                            <div class="flex justify-between">
                                <!-- <div class="font-medium"></div> -->
                                <span class="{{ $task->status === 'ditugaskan' ? 'text-yellow-600' : ($task->status === 'dikerjakan' ? 'text-blue-600' : 'text-green-600') }} text-sm">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $task->laporan->fasilitasRuang->fasilitas->nama_fasilitas }} -
                                {{ $task->laporan->fasilitasRuang->ruang->nama_ruang }}
                            </p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">{{ $task->created_at->format('d M Y') }}</span>
                                <a href="{{ route('tugas.show', $task->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Detail
                                </a>
                            </div>
                        </li>
                        @empty
                        <li class="py-4 px-6 text-center text-gray-500">
                            Tidak ada tugas terbaru.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Monthly Stats -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h2 class="text-lg font-semibold mb-4">Statistik Bulan Ini</h2>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600">Total Laporan</span>
                    <span class="font-bold">{{ $monthlyStats['total'] }}</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-600">Selesai</span>
                    <span class="font-bold text-green-600">{{ $monthlyStats['selesai'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tingkat Penyelesaian</span>
                    <span class="font-bold">
                        {{ $monthlyStats['total'] > 0 ? round(($monthlyStats['selesai'] / $monthlyStats['total']) * 100) : 0 }}%
                    </span>
                </div>
                <div class="mt-3 w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $monthlyStats['total'] > 0 ? ($monthlyStats['selesai'] / $monthlyStats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Priority Reports -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b px-4 py-3">
                    <h2 class="text-lg font-semibold">Laporan Prioritas Tinggi</h2>
                </div>
                <div class="p-4">
                    @if(count($priorityReports) > 0)
                    @foreach($priorityReports as $report)
                    <div class="border rounded-lg p-4 {{ $loop->index > 0 ? 'mt-4' : '' }} bg-red-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-red-800">
                                    {{ $report->fasilitasRuang->fasilitas->nama_fasilitas }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $report->fasilitasRuang->ruang->nama_ruang }} ({{ $report->fasilitasRuang->ruang->gedung->nama_gedung }})
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Prioritas {{ $report->ranking }}/5
                            </span>
                        </div>
                        <p class="mt-2 text-sm">{{ Str::limit($report->deskripsi, 80) }}</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-xs text-gray-500">
                                {{ $report->created_at->diffForHumans() }}
                            </span>
                            <div>
                                <a href="{{ route('tugas.create', $report->id_laporan) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-2 py-1 rounded text-xs mr-1">
                                    Tugaskan
                                </a>
                                <a href="{{ route('laporan.show', $report) }}" class="text-white bg-red-600 hover:bg-red-700 px-2 py-1 rounded text-xs">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4 text-gray-500">
                        Tidak ada laporan prioritas tinggi.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
    // Chart for reports trend
    const reportsCtx = document.getElementById('reportsChart').getContext('2d');
    const reportsChart = new Chart(reportsCtx, {
        type: 'line',
        data: {
            labels: {
                !!json_encode(array_column($dateRange, 'day')) !!
            },
            datasets: [{
                label: 'Jumlah Laporan',
                data: {
                    !!json_encode(array_column($dateRange, 'count')) !!
                },
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Chart for reports by location
    const locationData = {
        !!json_encode($reportsByLocation) !!
    };
    const locationLabels = locationData.map(item => item.nama_gedung);
    const locationCounts = locationData.map(item => item.total);

    const locationCtx = document.getElementById('locationChart').getContext('2d');
    const locationChart = new Chart(locationCtx, {
        type: 'doughnut',
        data: {
            labels: locationLabels,
            datasets: [{
                data: locationCounts,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection