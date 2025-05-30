@extends('layouts.app')

@section('title', 'Laporan Kerusakan Saya')

@section('content')
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
    <p>{{ session('success') }}</p>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Daftar Laporan Kerusakan</h1>

    <!-- Search & Add Button in one row -->
    <div class="flex justify-between items-center mb-4">
        <form id="searchForm" method="GET" action="{{ route('periode.index') }}" class="w-full max-w-xs">
            <div class="relative">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari laporan..."
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
            Tambah Laporan
        </button>
    </div>




    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">Nama Ruang</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">Nama Fasilitas</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">Kode Fasilitas</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($laporans as $laporan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        {{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        {{ $laporan->fasilitasRuang->kode_fasilitas ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($laporan->status == 'menunggu_verifikasi') bg-yellow-100 text-yellow-800
                            @elseif($laporan->status == 'diproses') bg-blue-100 text-blue-800
                            @elseif($laporan->status == 'diperbaiki') bg-indigo-100 text-indigo-800
                            @elseif($laporan->status == 'selesai') bg-green-100 text-green-800
                            @elseif($laporan->status == 'ditolak') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-center">
                        <button class="detail-btn text-blue-600 hover:text-blue-900 mr-2" data-id="{{ $laporan->id_laporan }}">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button class="edit-btn text-green-600 hover:text-green-900" data-id="{{ $laporan->id_laporan }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data laporan kerusakan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            @if($laporans->count() > 0)
                Menampilkan {{ $laporans->firstItem() }} sampai {{ $laporans->lastItem() }} dari {{ $laporans->total() }} hasil
            @else
                Tidak ada data
            @endif
        </div>
        <div>
            {{ $laporans->appends(['q' => request('q')])->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button id="closeDetailModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
        <h2 class="text-lg font-semibold mb-4">Detail Laporan</h2>
        <div id="modalContent">
            <div class="mb-2"><strong>Ruang:</strong> <span id="modalRuang"></span></div>
            <div class="mb-2"><strong>Fasilitas:</strong> <span id="modalFasilitas"></span></div>
            <div class="mb-2"><strong>Kode Fasilitas:</strong> <span id="modalKode"></span></div>
            <div class="mb-2"><strong>Deskripsi:</strong> <span id="modalDeskripsi"></span></div>
            <div class="mb-2"><strong>Gambar:</strong><br>
                <img id="modalGambar" src="" alt="Foto Laporan" class="mt-2 rounded shadow max-h-64">
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Laporan -->
<div id="addLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah Laporan</h3>
            <form id="addLaporanForm" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="add_ruang_id" class="block text-sm font-medium text-gray-700">Ruang</label>
                        <select id="add_ruang_id" name="ruang_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Ruang</option>
                            @foreach(\App\Models\Ruang::all() as $ruang)
                                <option value="{{ $ruang->id_ruang }}">{{ $ruang->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="add_fasilitas_id" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <select id="add_fasilitas_id" name="fasilitas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Fasilitas</option>
                        </select>
                    </div>

                    <div>
                        <label for="add_id_fas_ruang" class="block text-sm font-medium text-gray-700">Kode Fasilitas</label>
                        <select id="add_id_fas_ruang" name="id_fas_ruang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Kode Fasilitas</option>
                        </select>
                    </div>

                    <div>
                        <label for="add_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="add_deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>

                    <div>
                        <label for="add_url_foto" class="block text-sm font-medium text-gray-700">
                            Foto <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="file"
                            id="add_url_foto"
                            name="url_foto"
                            class="mt-1 block w-full"
                            accept="image/*"
                            required
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            Wajib mengunggah foto kerusakan (Maks. 2MB)
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Laporan -->
<div id="editLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Laporan</h3>
            <form id="editLaporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_ruang_id" class="block text-sm font-medium text-gray-700">Ruang</label>
                        <input type="text" id="edit_ruang_display" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                        <input type="hidden" id="edit_ruang_id" name="ruang_id">
                    </div>

                    <div>
                        <label for="edit_fasilitas_id" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <input type="text" id="edit_fasilitas_display" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                        <input type="hidden" id="edit_fasilitas_id" name="fasilitas_id">
                    </div>

                    <div>
                        <label for="edit_id_fas_ruang" class="block text-sm font-medium text-gray-700">Kode Fasilitas</label>
                        <input type="text" id="edit_fas_ruang_display" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                        <input type="hidden" id="edit_id_fas_ruang" name="id_fas_ruang">
                    </div>

                    <div>
                        <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>

                    <div>
                        <label for="edit_url_foto" class="block text-sm font-medium text-gray-700">Foto Baru (opsional)</label>
                        <input type="file" id="edit_url_foto" name="url_foto" class="mt-1 block w-full" accept="image/*">
                        <div id="current_photo" class="mt-2">
                            <p class="text-sm text-gray-500">Foto Saat Ini:</p>
                            <img id="edit_photo_preview" src="" alt="Foto Current" class="mt-2 max-h-40 rounded shadow">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Elements
    const detailModal = document.getElementById('detailModal');
    const addLaporanModal = document.getElementById('addLaporanModal');
    const editLaporanModal = document.getElementById('editLaporanModal');

    // Detail View Elements
    const modalRuang = document.getElementById('modalRuang');
    const modalFasilitas = document.getElementById('modalFasilitas');
    const modalKode = document.getElementById('modalKode');
    const modalDeskripsi = document.getElementById('modalDeskripsi');
    const modalGambar = document.getElementById('modalGambar');

    // Detail Button Handler
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const response = await fetch(`/laporan/detail/${id}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();

                // Update modal content
                document.getElementById('modalRuang').textContent = data.fasilitas_ruang?.ruang?.nama_ruang || '-';
                document.getElementById('modalFasilitas').textContent = data.fasilitas_ruang?.fasilitas?.nama_fasilitas || '-';
                document.getElementById('modalKode').textContent = data.fasilitas_ruang?.kode_fasilitas || '-';
                document.getElementById('modalDeskripsi').textContent = data.deskripsi || '-';

                const modalGambar = document.getElementById('modalGambar');
                if (data.url_foto) {
                    modalGambar.src = data.url_foto.startsWith('http') ?
                        data.url_foto : `/storage/${data.url_foto}`;
                    modalGambar.style.display = 'block';
                } else {
                    modalGambar.style.display = 'none';
                }

                document.getElementById('detailModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat detail laporan');
            }
        });
    });

    // Close Detail Modal
    document.getElementById('closeDetailModal').addEventListener('click', () => {
        detailModal.classList.add('hidden');
    });

    // Edit Button Handler with Fixed Form Submission
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const response = await fetch(`/laporan/detail/${id}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();

                // Set form action
                const editForm = document.getElementById('editLaporanForm');
                editForm.action = `/laporan/${id}`;

                // Set form values
                document.getElementById('edit_ruang_display').value = data.fasilitas_ruang?.ruang?.nama_ruang || '-';
                document.getElementById('edit_fasilitas_display').value = data.fasilitas_ruang?.fasilitas?.nama_fasilitas || '-';
                document.getElementById('edit_fas_ruang_display').value = data.fasilitas_ruang?.kode_fasilitas || '-';
                document.getElementById('edit_deskripsi').value = data.deskripsi || '';
                document.getElementById('edit_id_fas_ruang').value = data.fasilitas_ruang?.id_fas_ruang;

                // Handle photo preview
                const currentPhotoDiv = document.getElementById('current_photo');
                const photoPreview = document.getElementById('edit_photo_preview');

                if (data.url_foto) {
                    photoPreview.src = data.url_foto.startsWith('http') ?
                        data.url_foto : `/storage/${data.url_foto}`;
                    currentPhotoDiv.style.display = 'block';
                } else {
                    currentPhotoDiv.style.display = 'none';
                }

                document.getElementById('editLaporanModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data laporan');
            }
        });
    });

    // Edit form submission handler
    document.getElementById('editLaporanForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const result = await response.json();

            if (result.success) {
                // Create success message element
                const successMessage = document.createElement('div');
                successMessage.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4';
                successMessage.innerHTML = `<p>${result.message}</p>`;

                // Insert message at the top of the content
                const content = document.querySelector('.bg-white.rounded-lg');
                content.insertBefore(successMessage, content.firstChild);

                // Hide modal
                document.getElementById('editLaporanModal').classList.add('hidden');

                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(result.message || 'Update failed');
            }
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Gagal mengupdate laporan');
        }
    });

    // Modal Controls
    const addLaporanBtn = document.getElementById('addPeriodBtn');

    // Add Form Elements
    const addForm = document.getElementById('addLaporanForm');
    const addRuangSelect = document.getElementById('add_ruang_id');
    const addFasilitasSelect = document.getElementById('add_fasilitas_id');
    const addKodeSelect = document.getElementById('add_id_fas_ruang');

    // Edit Form Elements
    const editForm = document.getElementById('editLaporanForm');
    const editRuangDisplay = document.getElementById('edit_ruang_display');
    const editFasilitasDisplay = document.getElementById('edit_fasilitas_display');
    const editKodeDisplay = document.getElementById('edit_fas_ruang_display');

    // Modal Controls
    addLaporanBtn.addEventListener('click', () => {
        addLaporanModal.classList.remove('hidden');
        resetAddForm();
    });

    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            addLaporanModal.classList.add('hidden');
            editLaporanModal.classList.add('hidden');
        });
    });

    // Add Form Dynamic Dropdowns
    addRuangSelect.addEventListener('change', function() {
        updateFasilitasDropdown(this.value, addFasilitasSelect);
    });

    addFasilitasSelect.addEventListener('change', function() {
        if (addRuangSelect.value && this.value) {
            updateKodeDropdown(addRuangSelect.value, this.value, addKodeSelect);
        }
    });

    // Utility Functions
    function resetAddForm() {
        addForm.reset();
        addFasilitasSelect.innerHTML = '<option value="">Pilih Fasilitas</option>';
        addKodeSelect.innerHTML = '<option value="">Pilih Kode Fasilitas</option>';
    }

    function updateFasilitasDropdown(ruangId, select) {
        select.innerHTML = '<option value="">Memuat fasilitas...</option>';
        if (ruangId) {
            fetch(`/laporan/fasilitas-by-ruang/${ruangId}`)
                .then(res => res.json())
                .then(data => {
                    select.innerHTML = '<option value="">Pilih Fasilitas</option>' +
                        data.map(item => `<option value="${item.id_fasilitas}">${item.nama_fasilitas}</option>`).join('');
                });
        }
    }

    function updateKodeDropdown(ruangId, fasilitasId, select) {
        select.innerHTML = '<option value="">Memuat kode...</option>';
        fetch(`/laporan/kode-by-ruang-fasilitas/${ruangId}/${fasilitasId}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '<option value="">Pilih Kode Fasilitas</option>' +
                    data.map(item => `<option value="${item.id_fas_ruang}">${item.kode_fasilitas}</option>`).join('');
            });
    }
});
</script>
@endsection