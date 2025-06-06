@extends('layouts.app')

@section('title', 'Riwayat Laporan Selesai')

@section('content')
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-lg" role="alert">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <p>{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg" role="alert">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <p>{{ session('error') }}</p>
    </div>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-history mr-3 text-blue-600"></i>
            Daftar Riwayat Laporan Kerusakan
        </h1>
        <div class="text-sm text-gray-600">
            Total: <span class="font-semibold text-blue-600">{{ $laporans->total() }}</span> laporan
        </div>
    </div>

    <form id="searchForm" method="GET" action="{{ route('laporan.riwayat') }}" class="mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari laporan, ruang, fasilitas..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-6">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                </div>
        </div>
    </form>

    <div id="loadingIndicator" class="hidden text-center py-4">
        <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
        <p class="text-gray-600 mt-2">Memuat data...</p>
    </div>

    <div class="overflow-x-auto border rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-left">
                        <div class="flex items-center">
                            <i class="fas fa-door-open mr-2"></i>Ruang
                        </div>
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-left">
                        <div class="flex items-center">
                            <i class="fas fa-tools mr-2"></i>Fasilitas
                        </div>
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-left">
                        <div class="flex items-center">
                            <i class="fas fa-qrcode mr-2"></i>Kode
                        </div>
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-calendar mr-2"></i>Tanggal
                        </div>
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-info-circle mr-2"></i>Status
                        </div>
                    </th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-cogs mr-2"></i>Aksi
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody id="laporanTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($laporans as $laporan)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap align-middle text-left">
                        <div class="flex items-center">
                            <div class="ml-0"> <div class="text-sm font-medium text-gray-900">
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
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between bg-gray-50 px-4 py-3 rounded-lg">
        <div class="text-sm text-gray-700 mb-4 sm:mb-0">
            @if($laporans->count() > 0)
                Menampilkan <span class="font-semibold">{{ $laporans->firstItem() }}</span> 
                sampai <span class="font-semibold">{{ $laporans->lastItem() }}</span> 
                dari <span class="font-semibold">{{ $laporans->total() }}</span> hasil
            @endif
        </div>
        <div class="flex-1 flex justify-center sm:justify-end">
            {{ $laporans->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                Detail Laporan Kerusakan
            </h2>
            <button id="closeDetailModal" class="text-white hover:text-gray-200 transition-colors duration-200" title="Tutup">
                <i class="fas fa-times text-xl"></i>
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
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl z-10">
            <i class="fas fa-times"></i>
        </button>
        <img id="fullImage" src="" alt="Full Size Image" class="max-w-full max-h-full object-contain">
    </div>
</div>

@include('pages.laporan.create')
@include('pages.laporan.edit')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const detailModal = document.getElementById('detailModal');
    const addLaporanModal = document.getElementById('addLaporanModal');
    const editLaporanModal = document.getElementById('editLaporanModal');
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Utility Functions
    function showLoading() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    }

    function hideLoading() {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
    }

    function showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const notification = document.createElement('div');
        notification.className = `${bgColor} border-l-4 p-4 mb-4 rounded-r-lg`;
        notification.setAttribute('role', 'alert');
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${icon} mr-2"></i>
                <p>${message}</p>
            </div>
        `;

        const content = document.querySelector('.bg-white.rounded-lg');
        content.parentNode.insertBefore(notification, content);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.transition = 'opacity 0.5s ease-out';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 5000);
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
            
            // Reset form elements
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
                
                // Populate modal content
                document.getElementById('modalRuang').textContent = 
                    [data.fasilitasRuang?.ruang?.gedung?.nama_gedung, data.fasilitasRuang?.ruang?.nama_ruang]
                    .filter(Boolean).join(' - ') || '-';
                
                document.getElementById('modalFasilitas').textContent = 
                    data.fasilitasRuang?.fasilitas?.nama_fasilitas || '-';
                
                document.getElementById('modalKode').textContent = 
                    data.fasilitasRuang?.kode_fasilitas || '-';
                
                document.getElementById('modalDeskripsi').value = data.deskripsi || '-';
                
                // Handle image
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

    // Edit Modal (if exists)
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
                    
                    // Populate edit form
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
                    
                    // Handle photo preview
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
                        },
                        credentials: 'same-origin'
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

    // Step Navigation (if exists)
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
                const previewId = inputId.replace('url_foto', 'photo_preview'); // Corrected this line
                const preview = document.getElementById(previewId);
                
                if (file && preview) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                        preview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else if (preview) {
                    preview.src = '#'; // Clear previous image
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
        if (!detailModal.classList.contains('hidden')) { // Only restore body scroll if detailModal is open
            // No need to change body.style.overflow here if detailModal is still open, as it handles its own overflow
        } else {
            document.body.style.overflow = 'auto';
        }
    }

});
</script>
@endsection