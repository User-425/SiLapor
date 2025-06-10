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
            Total: <span class="font-semibold text-blue-600">{{ $laporans->total() }}</span> laporan
        </div>
    </div>

    <form id="searchForm" method="GET" action="{{ route('laporan.riwayat') }}" class="mb-6">
        <div class="relative">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari laporan, ruang, fasilitas..."
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
                            
                            @if($laporan->status == 'selesai' && !$laporan->umpanBaliks()->where('id_pengguna', Auth::id())->exists())
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

    @if($laporans->hasPages())
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

<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Detail Laporan Kerusakan</h2>
            <button id="closeDetailModal" class="text-gray-300 hover:text-gray-500 transition-colors duration-200" title="Tutup">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 max-h-[calc(90vh-80px)] overflow-y-auto">
            <div id="modalContent" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-info mr-2 text-blue-600"></i>
                            Informasi Laporan
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Ruang:</span>
                                <span id="modalRuang" class="text-gray-900 font-semibold"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Fasilitas:</span>
                                <span id="modalFasilitas" class="text-gray-900 font-semibold"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Kode:</span>
                                <span id="modalKode" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Status:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Selesai
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                            Deskripsi Kerusakan
                        </h3>
                        <textarea 
                            id="modalDeskripsi" 
                            class="w-full bg-white border border-gray-200 rounded-lg p-3 text-gray-800 resize-none focus:outline-none" 
                            rows="6" 
                            readonly
                        ></textarea>
                    </div>
                </div>

                <div>
                    <div class="bg-gray-50 p-4 rounded-lg h-full">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-camera mr-2 text-blue-600"></i>
                            Foto Kerusakan
                        </h3>
                        <div class="flex items-center justify-center bg-white rounded-lg border-2 border-dashed border-gray-200 h-80">
                            <img 
                                id="modalGambar" 
                                src="" 
                                alt="Foto Laporan" 
                                class="max-h-full max-w-full object-contain rounded-lg shadow-sm cursor-pointer"
                                onclick="openImageModal(this.src)"
                            >
                            <div id="modalNoImage" class="text-center text-gray-400 hidden">
                                <i class="fas fa-image text-4xl mb-2"></i>
                                <p>Tidak ada foto tersedia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    function showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const notification = document.createElement('div');
        notification.className = `${bgColor} border-l-4 p-4 mb-4 rounded-r-lg`;
        notification.setAttribute('role', 'alert');
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${icon} mr-2"></i>
                <p>${message}</p>
            </div>
            <button type="button" class="absolute top-2 right-2 text-${type === 'success' ? 'green' : 'red'}-400 hover:text-${type === 'success' ? 'green' : 'red'}-600" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        const content = document.querySelector('.bg-white.rounded-lg');
        content.parentNode.insertBefore(notification, content);

        setTimeout(() => notification.remove(), 5000);
    }

    // Modal Management
    function closeModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function openModal(modal) {
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    // Add Laporan Modal
    const addLaporanBtn = document.getElementById('addPeriodBtn');
    if (addLaporanBtn && addLaporanModal) {
        addLaporanBtn.addEventListener('click', function() {
            openModal(addLaporanModal);
            const addForm = document.getElementById('addLaporanForm');
            if (addForm) addForm.reset();
            
            const elements = [
                'add_photo_preview',
                'add_fasilitas_id',
                'add_id_fas_ruang',
                'step1',
                'step2'
            ];
            
            elements.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    if (id.includes('preview')) el.classList.add('hidden');
                    else if (id.includes('select')) el.innerHTML = '<option value="">Pilih...</option>';
                    else if (id === 'step1') el.classList.remove('hidden');
                    else if (id === 'step2') el.classList.add('hidden');
                }
            });
        });
    }

    // Close modal buttons
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            closeModal(addLaporanModal);
            closeModal(editLaporanModal);
        });
    });

    if (document.getElementById('closeDetailModal')) {
        document.getElementById('closeDetailModal').addEventListener('click', () => {
            closeModal(detailModal);
        });
    }

    // Detail Modal
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            showLoading();
            
            try {
                const response = await fetch(`/laporan/detail/${id}`);
                if (!response.ok) throw new Error('Gagal memuat data');
                
                const data = await response.json();
                
                document.getElementById('modalRuang').textContent = 
                    [data.fasilitasRuang?.ruang?.gedung?.nama_gedung, data.fasilitasRuang?.ruang?.nama_ruang]
                    .filter(Boolean).join(' - ') || '-';
                
                document.getElementById('modalFasilitas').textContent = 
                    data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
                
                document.getElementById('modalKode').textContent = 
                    data.fasilitasRuang?.kode_fasilitas || '-';
                
                document.getElementById('modalDeskripsi').value = data.deskripsi || '-';
                
                const modalGambar = document.getElementById('modalGambar');
                const modalNoImage = document.getElementById('modalNoImage');
                
                if (data.url_foto) {
                    modalGambar.src = data.url_foto;
                    modalGambar.classList.remove('hidden');
                    modalNoImage.classList.add('hidden');
                } else {
                    modalGambar.classList.add('hidden');
                    modalNoImage.classList.remove('hidden');
                }
                
                openModal(detailModal);
            } catch (error) {
                console.error('Error:', error);
                showNotification('Gagal memuat detail laporan', 'error');
            } finally {
                hideLoading();
            }
        });
    });

    // Edit Modal
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            showLoading();
            
            try {
                const response = await fetch(`/laporan/detail/${id}`);
                if (!response.ok) throw new Error('Gagal memuat data');
                
                const data = await response.json();
                const editForm = document.getElementById('editLaporanForm');
                
                if (editForm) {
                    editForm.action = `/laporan/${id}`;
                    
                    const fields = {
                        'edit_ruang_display': [data.fasilitasRuang?.ruang?.gedung?.nama_gedung, data.fasilitasRuang?.ruang?.nama_ruang].filter(Boolean).join(' - ') || '-',
                        'edit_fasilitas_display': data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-',
                        'edit_fas_ruang_display': data.fasilitasRuang?.kode_fasilitas || '-',
                        'edit_deskripsi': data.deskripsi || '',
                        'edit_id_fas_ruang': data.fasilitasRuang?.id_fas_ruang
                    };
                    
                    Object.entries(fields).forEach(([id, value]) => {
                        const element = document.getElementById(id);
                        if (element) element.value = value;
                    });
                    
                    const photoPreview = document.getElementById('edit_photo_preview');
                    const currentPhotoDiv = document.getElementById('current_photo');
                    
                    if (data.url_foto && photoPreview) {
                        photoPreview.src = data.url_foto;
                        if (currentPhotoDiv) currentPhotoDiv.style.display = 'block';
                    } else if (currentPhotoDiv) {
                        currentPhotoDiv.style.display = 'none';
                    }
                    
                    openModal(editLaporanModal);
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Gagal memuat data laporan', 'error');
            } finally {
                hideLoading();
            }
        });
    });

    // Form Submissions
    const forms = ['addLaporanForm', 'editLaporanForm'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const isEdit = formId === 'editLaporanForm';
                
                if (isEdit) formData.append('_method', 'PUT');
                
                showLoading();
                
                try {
                    const response = await fetch(this.action || (isEdit ? '' : '/laporan'), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        closeModal(isEdit ? editLaporanModal : addLaporanModal);
                        showNotification(result.message || `Laporan berhasil ${isEdit ? 'diupdate' : 'disimpan'}`);
                        
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        throw new Error(result.message || `Gagal ${isEdit ? 'mengupdate' : 'menyimpan'} laporan`);
                    }
                } catch (error) {
                    console.error('Form submission error:', error);
                    showNotification(error.message, 'error');
                } finally {
                    hideLoading();
                }
            });
        }
    });

    // Step Navigation
    const nextStepBtn = document.getElementById('nextStepBtn');
    const prevStepBtn = document.getElementById('prevStepBtn');
    
    if (nextStepBtn) {
        nextStepBtn.addEventListener('click', function() {
            const requiredFields = [
                'add_ruang_id',
                'add_fasilitas_id', 
                'add_id_fas_ruang',
                'add_deskripsi'
            ];
            
            const missingFields = requiredFields.filter(id => {
                const element = document.getElementById(id);
                return !element || !element.value.trim();
            });
            
            const fileInput = document.getElementById('add_url_foto');
            const hasFile = fileInput && fileInput.files.length > 0;
            
            if (missingFields.length > 0 || !hasFile) {
                showNotification('Mohon lengkapi semua data dan upload foto!', 'error');
                return;
            }
            
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        });
    }
    
    if (prevStepBtn) {
        prevStepBtn.addEventListener('click', function() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        });
    }

    // Photo Preview
    const photoInputs = ['add_url_foto', 'edit_url_foto'];
    photoInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const previewId = inputId.replace('url_foto', 'photo_preview');
                const preview = document.getElementById(previewId);
                
                if (file && preview) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                        preview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else if (preview) {
                    preview.src = '';
                    preview.classList.add('hidden');
                }
            });
        }
    });

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
            timeout = setTimeout(function() {
                showLoading();
                fetch(`{{ route('laporan.riwayat') }}?q=${encodeURIComponent(searchInput.value)}`)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTbody = doc.getElementById('laporanTableBody');
                        if (newTbody) {
                            tableBody.innerHTML = newTbody.innerHTML;
                            // Reattach detail button listeners
                            document.querySelectorAll('.detail-btn').forEach(btn => {
                                btn.addEventListener('click', async function() {
                                    const id = this.dataset.id;
                                    showLoading();
                                    try {
                                        const response = await fetch(`/laporan/detail/${id}`);
                                        if (!response.ok) throw new Error('Gagal memuat data');
                                        const data = await response.json();
                                        document.getElementById('modalRuang').textContent = 
                                            [data.fasilitasRuang?.ruang?.gedung?.nama_gedung, data.fasilitasRuang?.ruang?.nama_ruang]
                                            .filter(Boolean).join(' - ') || '-';
                                        document.getElementById('modalFasilitas').textContent = 
                                            data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
                                        document.getElementById('modalKode').textContent = 
                                            data.fasilitasRuang?.kode_fasilitas || '-';
                                        document.getElementById('modalDeskripsi').value = data.deskripsi || '-';
                                        const modalGambar = document.getElementById('modalGambar');
                                        const modalNoImage = document.getElementById('modalNoImage');
                                        if (data.url_foto) {
                                            modalGambar.src = data.url_foto;
                                            modalGambar.classList.remove('hidden');
                                            modalNoImage.classList.add('hidden');
                                        } else {
                                            modalGambar.classList.add('hidden');
                                            modalNoImage.classList.remove('hidden');
                                        }
                                        openModal(detailModal);
                                    } catch (error) {
                                        console.error('Error:', error);
                                        showNotification('Gagal memuat detail laporan', 'error');
                                    } finally {
                                        hideLoading();
                                    }
                                });
                            });
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