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
                            Detail
                        </button>
                        <button class="edit-btn text-green-600 hover:text-green-900" data-id="{{ $laporan->id_laporan }}">
                            Edit
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

<!-- Modal Tambah/Edit Laporan -->
<div id="laporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="laporanModalTitle">Tambah Laporan</h3>
            <div class="mt-2 px-7 py-3">
                <form id="laporanForm" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="laporan-method-field"></div>
                    <div class="mb-4">
                        <label for="ruang_id" class="block text-sm font-medium text-gray-700 text-left">Ruang</label>
                        <select id="ruang_id" name="ruang_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">Pilih Ruang</option>
                            @foreach(\App\Models\Ruang::all() as $ruang)
                                <option value="{{ $ruang->id_ruang }}">{{ $ruang->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="fasilitas_id" class="block text-sm font-medium text-gray-700 text-left">Fasilitas</label>
                        <select id="fasilitas_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">Pilih Fasilitas</option>
                            {{-- Akan diisi oleh JS --}}
                        </select>
                    </div>
                    <!-- Kode Fasilitas -->
                    <div class="mb-4">
                        <label for="id_fas_ruang" class="block text-sm font-medium text-gray-700 text-left">Kode Fasilitas</label>
                        <select name="id_fas_ruang" id="id_fas_ruang" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">Pilih Kode Fasilitas</option>
                            {{-- Akan diisi oleh JS --}}
                        </select>
                        <!-- Hidden input untuk mengirim value saat disabled -->
                        <input type="hidden" id="hidden_id_fas_ruang" name="id_fas_ruang">
                    </div>
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 text-left">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="mt-1 block w-full border-gray-300 rounded-md" required></textarea>
                        <p id="deskripsi-note" class="hidden text-xs text-gray-500 mt-1">Deskripsi tidak dapat diubah saat mengedit laporan.</p>
                    </div>
                    <div class="mb-4">
                        <label for="url_foto" class="block text-sm font-medium text-gray-700 text-left">Foto (opsional)</label>
                        <input type="file" name="url_foto" id="url_foto" class="mt-1 block w-full border-gray-300 rounded-md" accept="image/*">
                        <img id="previewFotoLama" src="" alt="Foto Sebelumnya" class="mt-2 rounded shadow max-h-40" style="display:none;">
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <button type="button" id="closeLaporanModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
document.addEventListener('DOMContentLoaded', function() {
    // Modal logic
    const modal = document.getElementById('detailModal');
    const closeModal = document.getElementById('closeDetailModal');
    const modalDeskripsi = document.getElementById('modalDeskripsi');
    const modalGambar = document.getElementById('modalGambar');
    const modalRuang = document.getElementById('modalRuang');
    const modalFasilitas = document.getElementById('modalFasilitas');
    const modalKode = document.getElementById('modalKode');
    const modalContent = document.getElementById('modalContent');

    // Show detail
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/laporan/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch details');
                    return res.json();
                })
                .then(data => {
                    console.log('Received data:', data); // Debug to see actual response structure
                    // Populate modal with data
                    modalRuang.textContent = data.fasilitasRuang?.ruang?.nama_ruang || '-';
                    modalFasilitas.textContent = data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
                    modalKode.textContent = data.fasilitasRuang?.kode_fasilitas || '-';
                    modalDeskripsi.textContent = data.deskripsi || '-';
                    if (data.url_foto) {
                        // Check if the URL is absolute (starts with http:// or https://)
                        if (data.url_foto.startsWith('http://') || data.url_foto.startsWith('https://')) {
                            modalGambar.src = data.url_foto;
                        } else {
                            // For relative paths stored using Laravel's storage system
                            modalGambar.src = `/storage/${data.url_foto}`;
                        }
                        modalGambar.style.display = 'block';
                    } else {
                        modalGambar.style.display = 'none';
                    }
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching laporan details:', error);
                    alert('Gagal memuat detail laporan.');
                });
        });
    });

    closeModal.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Modal Tambah Laporan
    const addLaporanBtn = document.getElementById('addPeriodBtn');
    const laporanModal = document.getElementById('laporanModal');
    const closeLaporanModal = document.getElementById('closeLaporanModal');
    const laporanForm = document.getElementById('laporanForm');
    const laporanMethodField = document.getElementById('laporan-method-field');
    const laporanModalTitle = document.getElementById('laporanModalTitle');

    function setFormMode(isEditMode) {
        const ruangSelect = document.getElementById('ruang_id');
        const fasilitasSelect = document.getElementById('fasilitas_id');
        const kodeSelect = document.getElementById('id_fas_ruang');
        const hiddenKode = document.getElementById('hidden_id_fas_ruang');

        if (isEditMode) {
            ruangSelect.disabled = true;
            fasilitasSelect.disabled = true;
            kodeSelect.disabled = true;
            // Set hidden input value
            hiddenKode.value = kodeSelect.value;
        } else {
            ruangSelect.disabled = false;
            fasilitasSelect.disabled = false;
            kodeSelect.disabled = false;
            hiddenKode.value = '';
        }
    }

    addLaporanBtn.addEventListener('click', function() {
        laporanModal.classList.remove('hidden');
        laporanModalTitle.textContent = 'Tambah Laporan';
        laporanForm.reset();
        laporanForm.action = "{{ route('laporan.store') }}";
        laporanMethodField.innerHTML = '';
        setFormMode(false); // Set to add mode
    });

    closeLaporanModal.addEventListener('click', function() {
        laporanModal.classList.add('hidden');
    });

    window.addEventListener('click', function(event) {
        if (event.target === laporanModal) {
            laporanModal.classList.add('hidden');
        }
    });

    // Dropdown dinamis fasilitas berdasarkan ruang
    const ruangSelect = document.getElementById('ruang_id');
    const fasilitasSelect = document.getElementById('fasilitas_id');
    const kodeSelect = document.getElementById('id_fas_ruang');

    ruangSelect.addEventListener('change', function() {
        fasilitasSelect.innerHTML = '<option value="">Memuat fasilitas...</option>';
        kodeSelect.innerHTML = '<option value="">Pilih Kode Fasilitas</option>';
        if (this.value) {
            fetch(`/laporan/fasilitas-by-ruang/${this.value}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Pilih Fasilitas</option>';
                    data.forEach(item => {
                        options += `<option value="${item.id_fasilitas}">${item.nama_fasilitas}</option>`;
                    });
                    fasilitasSelect.innerHTML = options;
                });
        } else {
            fasilitasSelect.innerHTML = '<option value="">Pilih Fasilitas</option>';
        }
    });

    fasilitasSelect.addEventListener('change', function() {
        kodeSelect.innerHTML = '<option value="">Memuat kode...</option>';
        if (ruangSelect.value && this.value) {
            fetch(`/laporan/kode-by-ruang-fasilitas/${ruangSelect.value}/${this.value}`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">Pilih Kode Fasilitas</option>';
                    data.forEach(item => {
                        options += `<option value="${item.id_fas_ruang}">${item.kode_fasilitas}</option>`;
                    });
                    kodeSelect.innerHTML = options;
                });
        } else {
            kodeSelect.innerHTML = '<option value="">Pilih Kode Fasilitas</option>';
        }
    });

    // Edit laporan
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;

            // Reset form
            laporanForm.reset();

            // Update form action and title
            laporanModalTitle.textContent = 'Edit Laporan';
            laporanForm.action = `/laporan/${id}`;

            // Add method PUT for update
            laporanMethodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Fetch laporan data for editing
            fetch(`/laporan/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                console.log('Edit data:', data);

                // Set ruang_id and trigger change event to load fasilitas
                if (data.fasilitasRuang?.ruang?.id_ruang) {
                    ruangSelect.value = data.fasilitasRuang.ruang.id_ruang;

                    // Trigger the change event to load fasilitas dropdown
                    const changeEvent = new Event('change');
                    ruangSelect.dispatchEvent(changeEvent);

                    // Wait a bit for fasilitas to load, then set fasilitas_id
                    setTimeout(() => {
                        if (data.fasilitasRuang?.fasilitas?.id_fasilitas) {
                            fasilitasSelect.value = data.fasilitasRuang.fasilitas.id_fasilitas;

                            // Trigger change to load kode fasilitas
                            fasilitasSelect.dispatchEvent(changeEvent);

                            // Set the id_fas_ruang after another small delay
                            setTimeout(() => {
                                if (data.fasilitasRuang?.id_fas_ruang) {
                                    kodeSelect.value = data.fasilitasRuang.id_fas_ruang;
                                    document.getElementById('hidden_id_fas_ruang').value = kodeSelect.value;
                            }
                        }, 500);
                        }
                    }, 500);
                }

                // Set deskripsi
                const deskripsiField = document.getElementById('deskripsi');
                deskripsiField.value = data.deskripsi || '';
                deskripsiField.readOnly = false; // BISA diedit

                // Tampilkan foto lama
                const previewFotoLama = document.getElementById('previewFotoLama');
                if (data.url_foto) {
                    if (data.url_foto.startsWith('http://') || data.url_foto.startsWith('https://')) {
                        previewFotoLama.src = data.url_foto;
                    } else {
                        previewFotoLama.src = `/storage/${data.url_foto}`;
                    }
                    previewFotoLama.style.display = 'block';
                } else {
                    previewFotoLama.style.display = 'none';
                }

                // Show the modal
                laporanModal.classList.remove('hidden');
                setFormMode(true);
            })
            .catch(error => {
                console.error('Error fetching laporan for edit:', error);
                alert('Gagal memuat data laporan untuk diedit.');
            });
        });
    });
});
</script>
@endsection
