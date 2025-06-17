@extends('layouts.app')

@section('title', 'Daftar Laporan Kerusakan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Flash Messages -->
    @if (session('success'))
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

    @if (session('error'))
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

    <div id="ajax-alert" class="hidden bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 relative rounded-r-lg" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <p id="ajax-alert-message" class="font-medium"></p>
        </div>
        <button type="button" class="absolute top-2 right-2 text-green-400 hover:text-green-600" onclick="this.parentElement.classList.add('hidden')">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6 mb-6">
        <!-- Card Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Laporan Kerusakan</h2>
            <div class="text-sm text-gray-600">
                Total: <span class="font-semibold text-blue-600">{{ $laporans->total() ?? $laporans->count() }}</span> laporan
            </div>
        </div>

        <!-- Search Form -->
        <form id="searchForm" method="GET" action="{{ route('laporan.index') }}" class="mb-6">
            <div class="relative">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari laporan, pelapor, fasilitas..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </form>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden text-center py-4">
            <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
            <p class="text-gray-600 mt-2">Memuat data...</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="laporanTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($laporans as $laporan)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                #{{ $laporan->id_laporan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $laporan->pengguna->nama_lengkap ?? 'N/A' }}
                                </div>
                                @if($laporan->pengguna->email ?? false)
                                <div class="text-sm text-gray-500">{{ $laporan->pengguna->email }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs overflow-hidden whitespace-nowrap text-ellipsis" title="{{ $laporan->deskripsi }}">
                                {{ Str::limit($laporan->deskripsi, 15, '...') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $laporan->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showDetail({{ $laporan->id_laporan }})"
                                    class="text-blue-600 hover:text-blue-900 transition duration-200"
                                    title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    class="edit-sarpras-btn text-yellow-600 hover:text-yellow-900 transition duration-200"
                                    data-id="{{ $laporan->id_laporan }}"
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('laporan.destroy', $laporan->id_laporan) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 transition duration-200"
                                        title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada laporan ditemukan</p>
                                <p class="text-gray-400 text-sm mt-1">Belum ada laporan kerusakan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($laporans, 'hasPages') && $laporans->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                @if($laporans->count() > 0)
                Menampilkan {{ $laporans->firstItem() }} sampai {{ $laporans->lastItem() }} dari {{ $laporans->total() }} laporan
                @else
                Tidak ada data
                @endif
            </div>
            <div>
                {{ $laporans->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Detail Laporan Kerusakan</h2>
                <button onclick="closeModal()" class="text-gray-300 hover:text-gray-500 transition-colors duration-200" title="Tutup">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 max-h-[calc(90vh-80px)] overflow-y-auto">
                <div id="detailContent" class="space-y-4">
                    <!-- Konten akan diisi via AJAX -->
                </div>
            </div>
        </div>
    </div>

    @include('pages.laporan.sarpras-edit')
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Utility Functions
    function showLoading() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    }

    function hideLoading() {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
    }

    function showNotification(message, type = 'success') {
        const alertBox = document.getElementById('ajax-alert');
        const messageEl = document.getElementById('ajax-alert-message');
        messageEl.textContent = message;
        alertBox.classList.remove('hidden', 'bg-red-50', 'border-red-400', 'bg-green-50', 'border-green-400');
        alertBox.classList.add(type === 'success' ? 'bg-green-50' : 'bg-red-50', type === 'success' ? 'border-green-400' : 'border-red-400');
        setTimeout(() => alertBox.classList.add('hidden'), type === 'success' ? 2000 : 3000);
    }

    // Modal Management
    function closeModal() {
        if (detailModal) detailModal.classList.add('hidden');
        document.getElementById('detailContent').innerHTML = '';
        document.body.style.overflow = '';
    }

    function openModal(modal) {
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    async function loadEditDataAndOpenModal(id) {
        showLoading();
        try {
            const response = await fetch(`/laporan/detail/${id}`);
            if (!response.ok) throw new Error('Gagal memuat data');
            const data = await response.json();

            const form = document.getElementById('sarprasEditLaporanForm');
            form.action = `/sarpras/laporan/${id}`;

            document.getElementById('edit_id_fas_ruang').value = data.fasilitasRuang?.id_fas_ruang || '';
            document.getElementById('sarprasEditLaporanId').value = data.id_laporan;
            document.getElementById('edit_deskripsi').value = data.deskripsi || '';
      
            // Set slider values and update their displays
            const kerusakanValue = data.kriteria?.tingkat_kerusakan_sarpras ?? 3;
            const dampakValue = data.kriteria?.dampak_akademik_sarpras ?? 3;
            const kebutuhanValue = data.kriteria?.kebutuhan_sarpras ?? 3;

            // Update main slider values
            document.getElementById('tingkat_kerusakan_sarpras').value = kerusakanValue;
            document.getElementById('dampak_akademik_sarpras').value = dampakValue;
            document.getElementById('kebutuhan_sarpras').value = kebutuhanValue;

            // Update the displayed values
            document.getElementById('kerusakan_value').textContent = kerusakanValue + '/5';
            document.getElementById('dampak_value').textContent = dampakValue + '/5';
            document.getElementById('jumlah_value').textContent = kebutuhanValue + '/5';

            // Update mobile slider values and displays if they exist
            if (document.getElementById('tingkat_kerusakan_sarpras_mobile')) {
                document.getElementById('tingkat_kerusakan_sarpras_mobile').value = kerusakanValue;
                document.getElementById('dampak_akademik_sarpras_mobile').value = dampakValue;
                document.getElementById('kebutuhan_sarpras_mobile').value = kebutuhanValue;

                document.getElementById('kerusakan_value_mobile').textContent = kerusakanValue + '/5';
                document.getElementById('dampak_value_mobile').textContent = dampakValue + '/5';
                document.getElementById('jumlah_value_mobile').textContent = kebutuhanValue + '/5';
            }

            document.getElementById('sarpras_edit_ruang').textContent =
                data.fasilitasRuang?.ruang?.nama_ruang ?
                `${data.fasilitasRuang.ruang.nama_ruang} - ${data.fasilitasRuang.ruang.gedung?.nama_gedung || ''}` : '-';
            document.getElementById('sarpras_edit_fasilitas').textContent = data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
            document.getElementById('sarpras_edit_kode').textContent = data.fasilitasRuang?.kode_fasilitas || '-';

            const photoPreview = document.getElementById('edit_photo_preview');
            const currentPhotoDiv = document.getElementById('current_photo');
            if (data.url_foto && photoPreview) {
                photoPreview.src = data.url_foto;
                if (currentPhotoDiv) currentPhotoDiv.style.display = 'block';
            } else if (currentPhotoDiv) {
                currentPhotoDiv.style.display = 'none';
            }
            document.getElementById('foto_lama').value = data.url_foto || '';

            openModal(document.getElementById('sarprasEditLaporanModal'));
        } catch (error) {
            console.error('Error:', error);
            showNotification('Gagal memuat data laporan', 'error');
        } finally {
            hideLoading();
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const successMsg = document.querySelector('.bg-green-50');
            const errorMsg = document.querySelector('.bg-red-50');
            if (successMsg) successMsg.style.display = 'none';
            if (errorMsg) errorMsg.style.display = 'none';
        }, 5000);
        const urlParams = new URLSearchParams(window.location.search);
        const reportId = urlParams.get('open_report');
        if (reportId) {
            setTimeout(() => {
                showDetail(reportId);
            }, 500);
            const newUrl = window.location.pathname + (urlParams.toString().replace(`open_report=${reportId}`, '').replace(/^&/, '?').replace(/^\?$/, ''));
            window.history.replaceState({}, document.title, newUrl || window.location.pathname);
        }

        // DOM Elements
        const detailModal = document.getElementById('detailModal');
        const sarprasEditModal = document.getElementById('sarprasEditLaporanModal');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const searchInput = document.querySelector('input[name="q"]');
        const tableBody = document.getElementById('laporanTableBody');
        let timeout = null;

        // AJAX for Detail
        window.showDetail = async function(id) {
            showLoading();
            try {
                const response = await fetch(`{{ url('laporan/detail') }}/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                if (!response.ok) throw new Error('Gagal memuat data');
                const data = await response.json();
                const content = `
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-info mr-2 text-blue-600"></i>
                            Informasi Laporan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">ID Laporan:</span>
                                <span class="text-gray-900 font-semibold">${data.id_laporan}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Pelapor:</span>
                                <span class="text-gray-900 font-semibold">${data.pengguna?.nama_lengkap || 'Tidak diketahui'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Email:</span>
                                <span class="text-gray-900 font-semibold">${data.pengguna?.email || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Fasilitas:</span>
                                <span class="text-gray-900 font-semibold">${data.fasilitasRuang?.fasilitas?.nama_fasilitas || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Ruang:</span>
                                <span class="text-gray-900 font-semibold">${data.fasilitasRuang?.ruang?.nama_ruang || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Gedung:</span>
                                <span class="text-gray-900 font-semibold">${data.fasilitasRuang?.ruang?.gedung?.nama_gedung || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.status_badge_class || 'bg-secondary text-muted'}">
                                    ${data.status_label || 'Tidak diketahui'}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Tanggal Dibuat:</span>
                                <span class="text-gray-900 font-semibold">${data.created_at}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                            Deskripsi Kerusakan
                        </h3>
                        <textarea class="w-full bg-white border border-gray-200 rounded-lg p-3 text-gray-800 resize-none focus:outline-none" rows="6" readonly>${data.deskripsi}</textarea>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-camera mr-2 text-blue-600"></i>
                            Foto Kerusakan
                        </h3>
                        <div class="flex items-center justify-center bg-white rounded-lg border-2 border-dashed border-gray-200 h-80">
                            ${data.url_foto ? `
                                <img src="${data.url_foto}" alt="Foto Kerusakan" class="max-h-full max-w-full object-contain rounded-lg shadow-sm cursor-pointer" onclick="openImageModal(this.src)">
                            ` : `
                                <div class="text-center text-gray-400">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <p>Tidak ada foto tersedia</p>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `;
                document.getElementById('detailContent').innerHTML = content;
                openModal(detailModal);
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat detail laporan.'
                });
            } finally {
                hideLoading();
            }
        };

        // Image Modal Functions
        window.openImageModal = function(src) {
            const imageModal = document.createElement('div');
            imageModal.id = 'imageModal';
            imageModal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-60 p-4';
            imageModal.innerHTML = `
            <div class="relative max-w-full max-h-full">
                <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
                <img id="fullImage" src="${src}" alt="Full Size Image" class="max-w-full max-h-full object-contain">
            </div>
        `;
            document.body.appendChild(imageModal);
            document.body.style.overflow = 'hidden';
        };

        window.closeImageModal = function() {
            const imageModal = document.getElementById('imageModal');
            if (imageModal) {
                imageModal.remove();
                if (!detailModal.classList.contains('hidden')) {
                    // Keep overflow hidden if detailModal is open
                } else {
                    document.body.style.overflow = '';
                }
            }
        };

        // Delete Confirmation
        window.confirmDelete = function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin hapus laporan ini?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
            return false;
        };

        // AJAX for Verification
        document.querySelectorAll('.verify-form select[name="status"]').forEach(select => {
            select.onchange = function() {
                showLoading();
                this.form.requestSubmit();
            };
        });

        // Edit Laporan Sarpras
        document.querySelectorAll('.edit-sarpras-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                await loadEditDataAndOpenModal(id);
            });
        });

        // Close Edit Modal
        document.querySelectorAll('#sarprasEditLaporanModal .close-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                sarprasEditModal.classList.add('hidden');
                document.getElementById('sarprasEditLaporanForm').reset();
                document.body.style.overflow = '';
            });
        });

        // Submit Edit Form
        document.getElementById('sarprasEditLaporanForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            showLoading();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    sarprasEditModal.classList.add('hidden');
                    showNotification(data.message || 'Data berhasil diupdate!');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Gagal mengupdate data.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengupdate data.', 'error');
            } finally {
                hideLoading();
            }
        });

        // Debounce Search
        if (searchInput && tableBody) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    showLoading();
                    fetch(`{{ route('laporan.index') }}?q=${encodeURIComponent(searchInput.value)}`)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newTbody = doc.getElementById('laporanTableBody');
                            if (newTbody) {
                                tableBody.innerHTML = newTbody.innerHTML;

                                // Reattach verification form handlers
                                document.querySelectorAll('.verify-form select[name="status"]').forEach(select => {
                                    select.onchange = function() {
                                        showLoading();
                                        this.form.requestSubmit();
                                    };
                                });

                                // Reattach edit button handlers
                                document.querySelectorAll('.edit-sarpras-btn').forEach(btn => {
                                    btn.addEventListener('click', async function() {
                                        const id = this.dataset.id;
                                        await loadEditDataAndOpenModal(id);
                                    });
                                });

                                // Reattach delete form handlers
                                document.querySelectorAll('form[onsubmit="return confirmDelete(event)"]').forEach(form => {
                                    form.onsubmit = function(event) {
                                        return confirmDelete(event);
                                    };
                                });
                            }

                            // Also update pagination if present
                            const paginationContainer = document.querySelector('.mt-6.flex.items-center.justify-between');
                            if (paginationContainer) {
                                const newPagination = doc.querySelector('.mt-6.flex.items-center.justify-between');
                                if (newPagination) {
                                    paginationContainer.innerHTML = newPagination.innerHTML;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            showNotification('Gagal memuat data', 'error');
                        })
                        .finally(() => hideLoading());
                }, 500);
            });
        }
    });
</script>
@endpush