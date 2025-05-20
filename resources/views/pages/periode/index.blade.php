@extends('layouts.app')

@section('title', 'Manajemen Periode')

@section('content')
<!-- Flash Messages for CRUD operations -->
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
    <p>{{ session('success') }}</p>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Daftar Periode</h1>

    <!-- Search & Add Button in one row -->
    <div class="flex justify-between items-center mb-4">
        <form id="searchForm" method="GET" action="{{ route('periode.index') }}" class="w-full max-w-xs">
            <div class="relative">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari periode..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </form>
        <button type="button" id="addPeriodBtn" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Tambah Periode
        </button>
    </div>

    <!-- Periods Table -->
    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">Nama Periode</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="periodeTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($periodes as $periode)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <div class="text-sm font-medium text-left text-gray-900">{{ $periode->nama_periode }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <div class="text-sm text-gray-900">{{ date('d-m-Y', strtotime($periode->tanggal_mulai)) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <div class="text-sm text-gray-900">{{ date('d-m-Y', strtotime($periode->tanggal_selesai)) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-sm font-medium text-center">
                        <div class="flex justify-center space-x-2">
                            <button class="edit-btn text-indigo-600 hover:text-indigo-900" data-id="{{ $periode->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <form action="{{ route('periode.destroy', $periode->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus periode ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data periode yang tersedia
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            @if($periodes->count() > 0)
                Menampilkan {{ $periodes->firstItem() }} sampai {{ $periodes->lastItem() }} dari {{ $periodes->total() }} hasil
            @else
                Tidak ada data
            @endif
        </div>
        <div>
            {{ $periodes->appends(['q' => request('q')])->links() }}
        </div>
    </div>
</div>

<!-- Create/Edit Period Modal (Hidden by default) -->
<div id="periodModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Tambah Periode</h3>
            <div class="mt-2 px-7 py-3">
                <form id="periodForm" action="{{ route('periode.store') }}" method="POST">
                    @csrf
                    <div id="method-field"></div>
                    <div class="mb-4">
                        <label for="nama_periode" class="block text-sm font-medium text-gray-700 text-left">Nama Periode</label>
                        <input type="text" name="nama_periode" id="nama_periode" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 text-left">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 text-left">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <button type="button" id="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
