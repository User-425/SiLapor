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
        <form id="searchForm" method="GET" action="{{ route('laporan.index') }}" class="w-full max-w-xs">
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
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-8 relative transition-all duration-200">
        <button id="closeDetailModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        <h2 class="text-2xl font-bold mb-6 text-indigo-700 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Detail Laporan
        </h2>
        <div id="modalContent" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-3">
                    <strong class="text-gray-700">Ruang:</strong>
                    <span id="modalRuang" class="text-gray-900"></span>
                </div>
                <div class="mb-3">
                    <strong class="text-gray-700">Fasilitas:</strong>
                    <span id="modalFasilitas" class="text-gray-900"></span>
                </div>
                <div class="mb-3">
                    <strong class="text-gray-700">Kode Fasilitas:</strong>
                    <span id="modalKode" class="text-gray-900"></span>
                </div>
                <div class="mb-3">
                    <strong class="text-gray-700">Deskripsi:</strong>
                    <textarea id="modalDeskripsi" class="bg-gray-50 rounded-lg p-3 mt-1 text-gray-800 shadow-inner w-full resize-none" rows="5" disabled></textarea>
                </div>
            </div>
            <div>
                <div class="mb-3">
                    <strong class="text-gray-700">Gambar:</strong><br>
                    <img id="modalGambar" src="" alt="Foto Laporan" class="mt-2 rounded-lg shadow max-h-72 w-full object-contain bg-gray-100">
                </div>
            </div>
        </div>
    </div>
</div>

@include('pages.laporan.create')
@include('pages.laporan.edit')
{{-- @include('pages.laporan.delete') jika ada modal hapus --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Elements
    const detailModal = document.getElementById('detailModal');
    const addLaporanModal = document.getElementById('addLaporanModal');
    const editLaporanModal = document.getElementById('editLaporanModal');

    // --- Tambah Laporan ---
    const addLaporanBtn = document.getElementById('addPeriodBtn');
    if (addLaporanBtn && addLaporanModal) {
        addLaporanBtn.addEventListener('click', function() {
            addLaporanModal.classList.remove('hidden');
            const addForm = document.getElementById('addLaporanForm');
            if (addForm) addForm.reset();
            document.getElementById('add_photo_preview').classList.add('hidden');
            document.getElementById('add_fasilitas_id').innerHTML = '<option value="">Pilih Fasilitas</option>';
            document.getElementById('add_id_fas_ruang').innerHTML = '<option value="">Pilih Kode Fasilitas</option>';
            document.getElementById('step1').classList.remove('hidden');
            document.getElementById('step2').classList.add('hidden');
        });
    }

    // --- Close Modal (semua modal) ---
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            addLaporanModal.classList.add('hidden');
            if (editLaporanModal) editLaporanModal.classList.add('hidden');
        });
    });
    if (document.getElementById('closeDetailModal')) {
        document.getElementById('closeDetailModal').addEventListener('click', () => {
            detailModal.classList.add('hidden');
        });
    }

    // --- Detail Laporan ---
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const response = await fetch(`/laporan/detail/${id}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                document.getElementById('modalRuang').textContent =
                    [data.fasilitasRuang?.ruang?.gedung?.nama_gedung, data.fasilitasRuang?.ruang?.nama_ruang].filter(Boolean).join(' - ') || '-';
                document.getElementById('modalFasilitas').textContent = data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
                document.getElementById('modalKode').textContent = data.fasilitasRuang?.kode_fasilitas || '-';
                document.getElementById('modalDeskripsi').value = data.deskripsi || '-';
                if (data.url_foto) {
                    document.getElementById('modalGambar').src = data.url_foto;
                    document.getElementById('modalGambar').style.display = 'block';
                } else {
                    document.getElementById('modalGambar').style.display = 'none';
                }
                detailModal.classList.remove('hidden');
            } catch (error) {
                alert('Gagal memuat detail laporan');
            }
        });
    });

    // --- Edit Laporan ---
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const response = await fetch(`/laporan/detail/${id}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                const editForm = document.getElementById('editLaporanForm');
                editForm.action = `/laporan/${id}`;
                document.getElementById('edit_ruang_display').value =
                    [data.fasilitasRuang?.ruang?.gedung?.nama_gedung, data.fasilitasRuang?.ruang?.nama_ruang].filter(Boolean).join(' - ') || '-';
                document.getElementById('edit_fasilitas_display').value = data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
                document.getElementById('edit_fas_ruang_display').value = data.fasilitasRuang?.kode_fasilitas || '-';
                document.getElementById('edit_deskripsi').value = data.deskripsi || '';
                document.getElementById('edit_id_fas_ruang').value = data.fasilitasRuang?.id_fas_ruang;
                const photoPreview = document.getElementById('edit_photo_preview');
                const currentPhotoDiv = document.getElementById('current_photo');
                if (data.url_foto) {
                    photoPreview.src = data.url_foto;
                    currentPhotoDiv.style.display = 'block';
                } else {
                    currentPhotoDiv.style.display = 'none';
                }
                editLaporanModal.classList.remove('hidden');
            } catch (error) {
                alert('Gagal memuat data laporan');
            }
        });
    });

    // --- Step Form Logic Tambah Laporan ---
    const nextStepBtn = document.getElementById('nextStepBtn');
    const prevStepBtn = document.getElementById('prevStepBtn');
    if (nextStepBtn) {
        nextStepBtn.addEventListener('click', function() {
            const ruang = document.getElementById('add_ruang_id').value;
            const fasilitas = document.getElementById('add_fasilitas_id').value;
            const kode = document.getElementById('add_id_fas_ruang').value;
            const deskripsi = document.getElementById('add_deskripsi').value;
            const foto = document.getElementById('add_url_foto').files.length > 0;
            if (!ruang || !fasilitas || !kode || !deskripsi || !foto) {
                alert('Mohon lengkapi semua data dan upload foto!');
                return;
            }
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            document.getElementById('step1BtnGroup').classList.add('hidden'); // tambahkan ini
        });
    }
    if (prevStepBtn) {
        prevStepBtn.addEventListener('click', function() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            document.getElementById('step1BtnGroup').classList.remove('hidden'); // tambahkan ini
        });
    }

    // --- Preview Foto Tambah Laporan ---
    const addUrlFoto = document.getElementById('add_url_foto');
    const addPhotoPreview = document.getElementById('add_photo_preview');
    if (addUrlFoto && addPhotoPreview) {
        addUrlFoto.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    addPhotoPreview.src = event.target.result;
                    addPhotoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                addPhotoPreview.classList.add('hidden');
                if (file) {
                    alert('File harus berupa gambar (JPG, PNG)');
                    this.value = '';
                }
            }
        });
    }

    // --- Dynamic Dropdown Tambah Laporan ---
    const addRuangSelect = document.getElementById('add_ruang_id');
    const addFasilitasSelect = document.getElementById('add_fasilitas_id');
    const addKodeSelect = document.getElementById('add_id_fas_ruang');
    addRuangSelect.addEventListener('change', function() {
        addFasilitasSelect.innerHTML = '<option value="">Memuat fasilitas...</option>';
        fetch(`/laporan/fasilitas-by-ruang/${this.value}`)
            .then(res => res.json())
            .then(data => {
                addFasilitasSelect.innerHTML = '<option value="">Pilih Fasilitas</option>' +
                    data.map(item => `<option value="${item.id_fasilitas}">${item.nama_fasilitas}</option>`).join('');
            });
        addKodeSelect.innerHTML = '<option value="">Pilih Kode Fasilitas</option>';
    });
        addFasilitasSelect.addEventListener('change', function() {
        if (addRuangSelect.value && this.value) {
            addKodeSelect.innerHTML = '<option value="">Memuat kode...</option>';
            fetch(`/laporan/kode-by-ruang-fasilitas/${addRuangSelect.value}/${this.value}`)
                .then(res => res.json())
                .then(data => { // <-- BENAR: Tambahkan tanda kurung di sini
                    addKodeSelect.innerHTML = '<option value="">Pilih Kode Fasilitas</option>' +
                        data.map(item => `<option value="${item.id_fas_ruang}">${item.kode_fasilitas}</option>`).join('');
                });
        }
    });

    // --- Submit Tambah Laporan ---
    document.getElementById('addLaporanForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            // Check if response is ok first
            if (!response.ok) {
                // Try to get more details about the error
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Server responded with an error');
                } else {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
            }

            const result = await response.json();
            if (result.success) {
                addLaporanModal.classList.add('hidden');

                // Tambahkan flash message di UI
                const flashContainer = document.createElement('div');
                flashContainer.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4';
                flashContainer.setAttribute('role', 'alert');
                flashContainer.innerHTML = `<p>Laporan berhasil disimpan</p>`;

                // Tempatkan flash message sebelum konten utama
                const content = document.querySelector('.bg-white.rounded-lg');
                content.parentNode.insertBefore(flashContainer, content);

                // Auto-hide flash message setelah 4 detik
                setTimeout(() => {
                    flashContainer.style.transition = 'opacity 0.5s';
                    flashContainer.style.opacity = '0';
                    setTimeout(() => flashContainer.remove(), 500);
                }, 4000);

                // Untuk reload data tanpa refresh penuh halaman (opsional)
                location.reload();
            } else {
                alert(result.message || 'Gagal menyimpan laporan');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            alert('Gagal menyimpan laporan: ' + error.message);
        }
    });

        // --- Submit Edit Laporan ---
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
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            });
            const result = await response.json();
            if (response.ok && result.success) {
                editLaporanModal.classList.add('hidden');

                // Tambahkan flash message di UI
                const flashContainer = document.createElement('div');
                flashContainer.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4';
                flashContainer.setAttribute('role', 'alert');
                flashContainer.innerHTML = `<p>Laporan berhasil diupdate</p>`;

                // Tempatkan flash message sebelum konten utama
                const content = document.querySelector('.bg-white.rounded-lg');
                content.parentNode.insertBefore(flashContainer, content);

                // Auto-hide flash message setelah 4 detik
                setTimeout(() => {
                    flashContainer.style.transition = 'opacity 0.5s';
                    flashContainer.style.opacity = '0';
                    setTimeout(() => flashContainer.remove(), 500);
                }, 4000);

                // Untuk reload data tanpa refresh penuh halaman (opsional)
                setTimeout(() => window.location.reload(), 1000);
            } else {
                alert(result.message || 'Gagal mengupdate laporan');
            }
        } catch (error) {
            alert('Gagal mengupdate laporan: ' + error.message);
        }
    });

    // --- Close modal jika klik di luar konten modal ---
    [detailModal, addLaporanModal, editLaporanModal].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) modal.classList.add('hidden');
            });
        }
    });
});
</script>
@endpush
