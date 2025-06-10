@extends('layouts.app')
@section('title', 'Manajemen Periode')
@section('content')
<!-- Flash Messages for CRUD operations -->
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 relative rounded-r-lg" role="alert">
    <div class="flex items-center">
        <svg class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    <button type="button" class="absolute top-2 right-2 text-green-400 hover:text-green-600" onclick="this.parentElement.style.display='none'">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Daftar Periode</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola periode akademik sistem</p>
        </div>
    </div>

    <div class="p-6">
        <!-- Search & Add Button in one row -->
        <div class="flex justify-between items-center mb-6">
            <form id="searchForm" method="GET" action="{{ route('periode.index') }}" class="w-full max-w-md">
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari periode..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </form>
            <button type="button" id="addPeriodBtn" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Periode
            </button>
        </div>

        <!-- Periods Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Periode</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="periodeTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($periodes as $periode)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $periode->nama_periode }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ date('d/m/Y', strtotime($periode->tanggal_mulai)) }}</div>
                                    <div class="text-xs text-gray-500">{{ date('l', strtotime($periode->tanggal_mulai)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ date('d/m/Y', strtotime($periode->tanggal_selesai)) }}</div>
                                    <div class="text-xs text-gray-500">{{ date('l', strtotime($periode->tanggal_selesai)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $start = new DateTime($periode->tanggal_mulai);
                                        $end = new DateTime($periode->tanggal_selesai);
                                        $diff = $start->diff($end);
                                        $days = $diff->days;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ $days }} hari
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="edit-btn inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200" 
                                                data-id="{{ $periode->id }}" title="Edit Periode">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('periode.destroy', $periode->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus periode {{ $periode->nama_periode }}?')"
                                                    title="Hapus Periode">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data periode</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan periode baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($periodes->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    @if($periodes->count() > 0)
                        Menampilkan {{ $periodes->firstItem() }} sampai {{ $periodes->lastItem() }} dari {{ $periodes->total() }} periode
                    @else
                        Tidak ada data
                    @endif
                </div>
                <div>
                    {{ $periodes->appends(['q' => request('q')])->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Create/Edit Period Modal (Hidden by default) -->
<div id="periodModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4" id="modalTitle">Tambah Periode</h3>
            <form id="periodForm" action="{{ route('periode.store') }}" method="POST">
                @csrf
                <div id="method-field"></div>
                <div class="space-y-4">
                    <div>
                        <label for="nama_periode" class="block text-sm font-medium text-gray-700 mb-1">Nama Periode</label>
                        <input type="text" name="nama_periode" id="nama_periode" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" id="closeModal" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.getElementById('addPeriodBtn');
    const modal = document.getElementById('periodModal');
    const closeModal = document.getElementById('closeModal');
    const form = document.getElementById('periodForm');
    const methodField = document.getElementById('method-field');
    let periodeId = null;

    // Show modal for adding new period
    addButton.addEventListener('click', function() {
        modal.classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Tambah Periode';
        form.reset();
        form.action = "{{ route('periode.store') }}";
        methodField.innerHTML = '';
        periodeId = null;
    });

    // Close modal
    closeModal.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Edit button functionality
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            periodeId = this.dataset.id;
            modal.classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Periode';

            // Set the form action for update
            form.action = `/periode/${periodeId}`;
            methodField.innerHTML = `@method('PUT')`;

            // Fetch period data and populate form
            fetch(`/periode/${periodeId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('nama_periode').value = data.nama_periode;
                    document.getElementById('tanggal_mulai').value = data.tanggal_mulai;
                    document.getElementById('tanggal_selesai').value = data.tanggal_selesai;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengambil data periode. Silakan coba lagi.');
                });
        });
    });

    // Auto-hide success message after 5 seconds
    setTimeout(function() {
        const successMsg = document.querySelector('.bg-green-50');
        if (successMsg) {
            successMsg.style.display = 'none';
        }
    }, 5000);
});

// Debounce search form submission
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('input[name="q"]');
    const tableBody = document.getElementById('periodeTableBody');
    let timeout = null;

    if (searchInput && tableBody) {
        searchInput.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                fetch(`{{ route('periode.index') }}?q=${encodeURIComponent(searchInput.value)}`)
                    .then(response => response.text())
                    .then(html => {
                        // Ambil tbody dari response HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTbody = doc.getElementById('periodeTableBody');
                        if (newTbody) {
                            tableBody.innerHTML = newTbody.innerHTML;
                        }
                    });
            }, 500);
        });
    }
});
</script>
@endpush