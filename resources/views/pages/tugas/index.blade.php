@extends('layouts.app')

@section('title', 'Penugasan Teknisi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        {{-- <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Tugaskan Teknisi</h1>
            <p class="text-gray-600 mt-2">Daftar laporan kerusakan yang belum ditugaskan ke teknisi</p>
        </div> --}}

        <!-- Alert Success -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Belum Ditugaskan</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ $laporanBelumDitugaskan->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">📋 Laporan Belum Ditugaskan</h2>
            </div>

            @if($laporanBelumDitugaskan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Laporan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas Ruangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($laporanBelumDitugaskan as $laporan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $laporan->id_laporan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                                <span class="text-xs font-medium text-indigo-600">
                                                    {{ strtoupper(substr($laporan->pengguna->nama ?? 'N', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ $laporan->pengguna->nama ?? 'N/A' }}</div>
                                                <div class="text-gray-500 text-xs">{{ $laporan->pengguna->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-medium">{{ $laporan->fasilitas->nama_fasilitas ?? 'N/A' }}</div>
                                                <div class="text-gray-500 text-xs">{{ $laporan->fasilitasRuangan->ruangan->nama_ruang ?? 'N/A' }} - {{ $laporan->fasilitasRuangan->ruangan->gedung->nama_gedung ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="max-w-xs">
                                            <div class="truncate" title="{{ $laporan->deskripsi }}">
                                                {{ Str::limit($laporan->deskripsi, 100) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(isset($laporan->ranking))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($laporan->ranking <= 3)
                                                    bg-red-100 text-red-800
                                                @elseif($laporan->ranking <= 7)
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-green-100 text-green-800
                                                @endif
                                            ">
                                                #{{ $laporan->ranking }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⏳ Belum Ditugaskan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tugas.create', $laporan->id_laporan) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                                🔧 Tugaskan
                                            </a>
                                            
                                            <!-- Quick View Button -->
                                            <button onclick="showDetail({{ $laporan->id_laporan }})" 
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                                👁️ Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada laporan</h3>
                    <p class="mt-1 text-sm text-gray-500">Saat ini tidak ada laporan kerusakan yang belum ditugaskan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Detail Laporan Kerusakan
                        </h3>
                        <div id="modalContent" class="space-y-4">
                            <!-- Content will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="hideDetail()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(id_laporan) {
    document.getElementById('detailModal').classList.remove('hidden');
    
    // Fetch detail via AJAX
    fetch(`/api/laporan/${id_laporan}/detail`)
        .then(response => response.json())
        .then(data => {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Laporan</label>
                        <p class="mt-1 text-sm text-gray-900">#${data.id_laporan}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pelapor</label>
                        <p class="mt-1 text-sm text-gray-900">${data.pengguna?.nama || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fasilitas Ruangan</label>
                        <p class="mt-1 text-sm text-gray-900">${data.fasilitas_ruangan?.nama_fasilitas || 'N/A'}</p>
                        <p class="text-xs text-gray-500">${data.fasilitas_ruangan?.ruangan?.nama_ruang || 'N/A'} - ${data.fasilitas_ruangan?.ruangan?.gedung?.nama_gedung || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Laporan</label>
                        <p class="mt-1 text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID')} ${new Date(data.created_at).toLocaleTimeString('id-ID')}</p>
                    </div>
                    ${data.ranking ? `
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ranking Urgensi</label>
                        <p class="mt-1 text-sm text-gray-900">#${data.ranking}</p>
                    </div>
                    ` : ''}
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">${data.deskripsi}</p>
                </div>
                ${data.url_foto ? `
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Kerusakan</label>
                    <img src="${data.url_foto}" alt="Foto kerusakan" class="max-w-full h-auto rounded-lg shadow-sm border">
                </div>
                ` : ''}
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = '<p class="text-red-600">Gagal memuat detail laporan.</p>';
        });
}

function hideDetail() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>
@endsection