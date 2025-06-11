@extends('layouts.app')

@section('title', 'Riwayat Laporan Selesai')

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
        <h1 class="text-xl font-semibold text-gray-800">Daftar Riwayat Laporan Kerusakan</h1>
        <div class="text-sm text-gray-600">
            Total: <span class="font-semibold text-blue-600">{{ method_exists($laporans, 'total') ? $laporans->total() : $laporans->count() }}</span> laporan
        </div>
    </div>

    <form id="searchForm" method="GET" action="{{ route('laporan.riwayat') }}" class="mb-6">
        <div class="relative">
            <input
                type="search"
                name="q"
                value="{{ request()->get('q') }}" 
                placeholder="Cari berdasarkan ruang, fasilitas, atau kode..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Selesai
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <button 
                                class="detail-btn inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200" 
                                data-id="{{ $laporan->id_laporan }}"
                                title="Lihat Detail"
                            >
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
                            @if(auth()->user()->peran !== 'sarpras' && $laporan->status == 'selesai' && !$laporan->umpanBaliks()->where('id_pengguna', Auth::id())->exists())
                            <a 
                                href="{{ route('umpan_balik.create', $laporan->id_laporan) }}"
                                class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-colors duration-200"
                                title="Beri Umpan Balik"
                            >
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
                            <p class="text-gray-500">Belum ada laporan kerusakan yang selesai</p>
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
            {{ $laporans->links() }}
        </div>
    </div>
    @endif
</div>

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
    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.querySelector('.bg-green-50');
        const errorMsg = document.querySelector('.bg-red-50');
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
    }, 5000);

    // DOM Elements
    const detailModal = document.getElementById('detailModal');
    const addLaporanModal = document.getElementById('addLaporanModal');
    const editLaporanModal = document.getElementById('editLaporan形態');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const searchInput = document.querySelector('input[name="q"]');
    const tableBody = document.getElementById('laporanTableBody');
    let timeout = null;

    // Utility Functions
    function showLoading() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    }

    function hideLoading() {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
    }

    // Image Modal Functions
    window.openImageModal = function(src) {
        document.getElementById('fullImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    window.closeImageModal = function() {
        document.getElementById('imageModal').classList.add('hidden');
        if (!detailModal.classList.contains('hidden')) {
            // Keep overflow hidden if detailModal is open
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    // Debounce Search
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