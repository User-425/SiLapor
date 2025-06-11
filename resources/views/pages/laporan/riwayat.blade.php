@extends('layouts.app')

@section('title', 'Riwayat Laporan')

@section('content')
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 relative rounded-r-lg" role="alert">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    <button type="button" class="absolute top-2 right-2 text-green-400 hover:text-green-600" onclick="this.parentElement.style.display='none'">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 relative rounded-r-lg" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    <button type="button" class="absolute top-2 right-2 text-red-400 hover:text-red-600" onclick="this.parentElement.style.display='none'">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Riwayat Laporan Kerusakan</h1>
        <div class="text-sm text-gray-600">
            Total: <span class="font-semibold text-blue-600">{{ method_exists($laporans, 'total') ? $laporans->total() : $laporans->count() }}</span> laporan
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('laporan.riwayat', ['tab' => 'selesai'] + request()->only(['q', 'tanggal_dari', 'tanggal_sampai'])) }}" 
               class="tab-link {{ $tab === 'selesai' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-check-circle mr-2"></i>
                Selesai
                <span class="ml-2 bg-green-100 text-green-800 py-0.5 px-2 rounded-full text-xs font-medium">{{ $selesaiCount }}</span>
            </a>
            <a href="{{ route('laporan.riwayat', ['tab' => 'ditolak'] + request()->only(['q', 'tanggal_dari', 'tanggal_sampai'])) }}" 
               class="tab-link {{ $tab === 'ditolak' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-times-circle mr-2"></i>
                Ditolak
                <span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2 rounded-full text-xs font-medium">{{ $ditolakCount }}</span>
            </a>
        </nav>
    </div>

    <form id="searchForm" method="GET" action="{{ route('laporan.riwayat') }}" class="mb-6">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <input
                    type="search"
                    name="q"
                    value="{{ request()->get('q') }}"
                    placeholder="Cari berdasarkan ruang, fasilitas, atau kode..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
            <div>
                <input
                    type="date"
                    name="tanggal_dari"
                    value="{{ request()->get('tanggal_dari') }}"
                    placeholder="Tanggal Dari"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <input
                    type="date"
                    name="tanggal_sampai"
                    value="{{ request()->get('tanggal_sampai') }}"
                    placeholder="Tanggal Sampai"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                <i class="fas fa-search mr-2"></i>
                Cari
            </button>
            <a href="{{ route('laporan.riwayat', ['tab' => $tab]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                <i class="fas fa-undo mr-2"></i>
                Reset
            </a>
        </div>
    </form>

    <div id="loadingIndicator" class="hidden text-center py-4">
        <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
        <p class="text-gray-600 mt-2">Memuat data...</p>
    </div>

    <div class="overflow-x-auto border rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($laporans as $laporan)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        <div class="flex items-center">
                            <div class="ml-0">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $laporan->fasilitasRuang->kode_fasilitas ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <div class="text-sm text-gray-900">
                            {{ $laporan->created_at->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $laporan->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        @if($laporan->status === 'selesai')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Selesai
                            </span>
                        @elseif($laporan->status === 'ditolak')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <button
                                class="detail-btn inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                                data-id="{{ $laporan->id_laporan }}"
                                title="Lihat Detail">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
                            @if(auth()->user()->peran !== 'sarpras' && $laporan->status == 'selesai' && !$laporan->umpanBaliks()->where('id_pengguna', Auth::id())->exists())
                            <a
                                href="{{ route('umpan_balik.create', $laporan->id_laporan) }}"
                                class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-colors duration-200"
                                title="Beri Umpan Balik">
                                <i class="fas fa-star mr-1"></i>
                                Umpan Balik
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data laporan</h3>
                            <p class="text-gray-500">
                                @if($tab === 'selesai')
                                    Belum ada laporan kerusakan yang selesai
                                @else
                                    Belum ada laporan kerusakan yang ditolak
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($laporans, 'hasPages') && $laporans->hasPages())
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing
            <span class="font-medium">{{ $laporans->firstItem() }}</span>
            to
            <span class="font-medium">{{ $laporans->lastItem() }}</span>
            of
            <span class="font-medium">{{ $laporans->total() }}</span>
            results
        </div>
        <div>
            {{ $laporans->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Modal content remains the same -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900" id="detailModalTitle">Detail Laporan</h3>
            <button type="button" id="closeDetailModal" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-6 max-h-[calc(90vh-80px)] overflow-y-auto" id="detailModalContent">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-60 p-4">
    <div class="relative max-w-full max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <img id="fullImage" src="" alt="Full Size Image" class="max-w-full max-h-full object-contain">
    </div>
</div>

@include('pages.laporan.create')
@include('pages.laporan.edit')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const successMsg = document.querySelector('.bg-green-50');
            const errorMsg = document.querySelector('.bg-red-50');
            if (successMsg) successMsg.style.display = 'none';
            if (errorMsg) errorMsg.style.display = 'none';
        }, 5000);

        const detailModal = document.getElementById('detailModal');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const searchInput = document.querySelector('input[name="q"]');
        const tableBody = document.getElementById('laporanTableBody');
        let timeout = null;

        function showLoading() {
            if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        }

        function hideLoading() {
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        }

        window.openImageModal = function(src) {
            document.getElementById('fullImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.closeImageModal = function() {
            document.getElementById('imageModal').classList.add('hidden');
            if (!detailModal.classList.contains('hidden')) {
                // Keep body overflow hidden if detail modal is still open
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        // Detail modal functionality
        const detailButtons = document.querySelectorAll('.detail-btn');
        const detailModalContent = document.getElementById('detailModalContent');
        const closeDetailModalBtn = document.getElementById('closeDetailModal');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.getAttribute('data-id');
                showLoading();
                fetch(`/laporan/detail/${reportId}`)
                    .then(response => response.json())
                    .then(data => {
                        detailModalContent.innerHTML = renderDetailContent(data);
                        detailModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    })
                    .catch(error => console.error('Error fetching report details:', error))
                    .finally(() => hideLoading());
            });
        });

        if (closeDetailModalBtn) {
            closeDetailModalBtn.addEventListener('click', function() {
                detailModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
        }

        function renderDetailContent(data) {
            const statusInfo = getStatusInfo(data.status);
            
            return `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Informasi Laporan</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Kode Laporan</p>
                                <p class="font-medium">${data.id_laporan || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusInfo.class}">
                                    <i class="${statusInfo.icon} mr-1"></i>
                                    ${statusInfo.label}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Laporan</p>
                                <p class="font-medium">${data.created_at || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal ${data.status === 'selesai' ? 'Penyelesaian' : 'Penolakan'}</p>
                                <p class="font-medium">${data.updated_at || '-'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right column -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Detail Fasilitas</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Ruang</p>
                                <p class="font-medium">${data.fasilitasRuang?.ruang?.nama_ruang || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Gedung</p>
                                <p class="font-medium">${data.fasilitasRuang?.ruang?.gedung?.nama_gedung || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fasilitas</p>
                                <p class="font-medium">${data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kode Fasilitas</p>
                                <p class="font-medium">${data.fasilitasRuang?.kode_fasilitas || '-'}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Deskripsi Kerusakan</h4>
                    <p class="text-gray-800">${data.deskripsi || '-'}</p>
                </div>
                
                ${data.url_foto ? `
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Foto Kerusakan</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <img src="${data.url_foto}" alt="Foto Kerusakan" 
                            class="cursor-pointer rounded-lg border border-gray-200 hover:opacity-90 transition-opacity"
                            onclick="openImageModal('${data.url_foto}')">
                    </div>
                </div>` : ''}
                
                ${data.status === 'selesai' ? `
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Catatan Penyelesaian</h4>
                    <p class="text-gray-800">${data.catatan_penyelesaian || '-'}</p>
                </div>` : ''}
                
                ${data.status === 'ditolak' ? `
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Alasan Penolakan</h4>
                    <p class="text-gray-800">${data.alasan_penolakan || '-'}</p>
                </div>` : ''}
            `;
        }

        function getStatusInfo(status) {
            switch(status) {
                case 'selesai':
                    return {
                        class: 'bg-green-100 text-green-800',
                        icon: 'fas fa-check-circle',
                        label: 'Selesai'
                    };
                case 'ditolak':
                    return {
                        class: 'bg-red-100 text-red-800',
                        icon: 'fas fa-times-circle',
                        label: 'Ditolak'
                    };
                default:
                    return {
                        class: 'bg-gray-100 text-gray-800',
                        icon: 'fas fa-question-circle',
                        label: status
                    };
            }
        }

        // Search functionality with debounce
        if (searchInput && tableBody) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        }
    });
</script>
@endpush