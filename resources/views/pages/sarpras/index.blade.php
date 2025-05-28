@extends('layouts.app')

@section('title', 'Manajemen Laporan Kerusakan - Sarpras')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Manajemen Laporan Kerusakan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Laporan Kerusakan</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $statistik['menunggu_verifikasi'] }}</h5>
                            <p class="card-text small">Menunggu Verifikasi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $statistik['diproses'] }}</h5>
                            <p class="card-text small">Diproses</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $statistik['diperbaiki'] }}</h5>
                            <p class="card-text small">Diperbaiki</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $statistik['selesai'] }}</h5>
                            <p class="card-text small">Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $statistik['ditolak'] }}</h5>
                            <p class="card-text small">Ditolak</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('sarpras.laporan.index') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                    <option value="menunggu_verifikasi" {{ $status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="diperbaiki" {{ $status == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Ranking</label>
                                <select name="ranking" class="form-select">
                                    <option value="">Semua Ranking</option>
                                    <option value="1" {{ request('ranking') == '1' ? 'selected' : '' }}>1 - Sangat Tinggi (Urgent)</option>
                                    <option value="2" {{ request('ranking') == '2' ? 'selected' : '' }}>2 - Tinggi</option>
                                    <option value="3" {{ request('ranking') == '3' ? 'selected' : '' }}>3 - Sedang</option>
                                    <option value="4" {{ request('ranking') == '4' ? 'selected' : '' }}>4 - Rendah</option>
                                    <option value="5" {{ request('ranking') == '5' ? 'selected' : '' }}>5 - Sangat Rendah</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('sarpras.laporan.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Laporan -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Laporan Kerusakan</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="batchUpdate()">
                            <i class="fas fa-edit"></i> Batch Update
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="exportData()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($laporans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>ID</th>
                                        <th>Pelapor</th>
                                        <th>Fasilitas</th>
                                        <th>Ruang</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Ranking</th>
                                        <th>Tanggal</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporans as $laporan)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_laporan[]" value="{{ $laporan->id_laporan }}" class="laporan-checkbox">
                                        </td>
                                        <td><strong>#{{ $laporan->id_laporan }}</strong></td>
                                        <td>
                                            <div>
                                                <strong>{{ $laporan->pengguna->nama ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $laporan->pengguna->email ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $laporan->fasilitasRuang->kode_fasilitas ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $laporan->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}</td>
                                        <td>
                                            <div style="max-width: 200px;">
                                                {{ Str::limit($laporan->deskripsi, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $laporan->status_badge }}">
                                                {{ $laporan->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($laporan->ranking)
                                                <span class="badge bg-{{ $laporan->ranking_color }}">
                                                    {{ $laporan->ranking }} - {{ $laporan->ranking_label }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Belum Ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $laporan->created_at->format('d/m/Y H:i') }}<br>
                                                <span class="text-muted">{{ $laporan->created_at->diffForHumans() }}</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-info" 
                                                        onclick="showDetail({{ $laporan->id_laporan }})" 
                                                        title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="showVerifikasi({{ $laporan->id_laporan }})" 
                                                        title="Verifikasi">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-warning" 
                                                        onclick="updateRanking({{ $laporan->id_laporan }}, {{ $laporan->ranking ?? 0 }})" 
                                                        title="Update Ranking">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Menampilkan {{ $laporans->firstItem() }} - {{ $laporans->lastItem() }} 
                                    dari {{ $laporans->total() }} laporan
                                </small>
                            </div>
                            <div>
                                {{ $laporans->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada laporan ditemukan</h5>
                            <p class="text-muted">Silakan ubah filter atau tunggu laporan baru masuk.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan Kerusakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formVerifikasi">
                <div class="modal-body">
                    <input type="hidden" id="laporanId" name="laporan_id">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ranking Prioritas</label>
                        <select name="ranking" class="form-select">
                            <option value="">Pilih Ranking</option>
                            <option value="1">1 - Sangat Tinggi (Urgent)</option>
                            <option value="2">2 - Tinggi</option>
                            <option value="3">3 - Sedang</option>
                            <option value="4">4 - Rendah</option>
                            <option value="5">5 - Sangat Rendah</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-top: none;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .btn-group-sm > .btn {
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
// Global variables
let selectedLaporanId = null;

// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.laporan-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Show detail modal
function showDetail(laporanId) {
    selectedLaporanId = laporanId;
    
    // Reset modal content
    document.getElementById('modalDetailContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();
    
    // Fetch detail data
    fetch(`{{ route('sarpras.laporan.show', ':id') }}`.replace(':id', laporanId), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Laporan</h6>
                    <table class="table table-sm">
                        <tr><td width="120"><strong>ID Laporan</strong></td><td>#${data.id_laporan}</td></tr>
                        <tr><td><strong>Status</strong></td><td><span class="badge bg-${getStatusBadge(data.status)}">${data.status}</span></td></tr>
                        <tr><td><strong>Ranking</strong></td><td>${data.ranking ? data.ranking + ' - ' + getRankingLabel(data.ranking) : 'Belum Ditentukan'}</td></tr>
                        <tr><td><strong>Dibuat</strong></td><td>${data.created_at}</td></tr>
                        <tr><td><strong>Diupdate</strong></td><td>${data.updated_at}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Pelapor</h6>
                    <table class="table table-sm">
                        <tr><td width="80"><strong>Nama</strong></td><td>${data.pengguna.nama}</td></tr>
                        <tr><td><strong>Email</strong></td><td>${data.pengguna.email}</td></tr>
                        <tr><td><strong>Role</strong></td><td>${data.pengguna.role}</td></tr>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Fasilitas</h6>
                    <table class="table table-sm">
                        <tr><td width="120"><strong>Nama Fasilitas</strong></td><td>${data.fasilitasRuang.fasilitas.nama_fasilitas}</td></tr>
                        <tr><td><strong>Kode Fasilitas</strong></td><td>${data.fasilitasRuang.kode_fasilitas}</td></tr>
                        <tr><td><strong>Ruang</strong></td><td>${data.fasilitasRuang.ruang.nama_ruang}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    ${data.url_foto ? `
                    <h6 class="text-muted">Foto Kerusakan</h6>
                    <img src="${data.url_foto}" class="img-fluid rounded" style="max-height: 200px;" alt="Foto Kerusakan">
                    ` : ''}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-muted">Deskripsi Kerusakan</h6>
                    <p class="border p-3 rounded bg-light">${data.deskripsi}</p>
                </div>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('modalDetailContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Gagal memuat detail laporan.
            </div>
        `;
    });
}

// Show verifikasi modal
function showVerifikasi(laporanId) {
    selectedLaporanId = laporanId;
    document.getElementById('laporanId').value = laporanId;
    
    const modal = new bootstrap.Modal(document.getElementById('modalVerifikasi'));
    modal.show();
}

// Update ranking
function updateRanking(laporanId, currentRanking) {
    const ranking = prompt(`Update Ranking untuk Laporan #${laporanId}\n\nRanking saat ini: ${currentRanking || 'Belum ditentukan'}\n\nMasukkan ranking baru (1-5):\n1 = Sangat Tinggi (Urgent)\n2 = Tinggi\n3 = Sedang\n4 = Rendah\n5 = Sangat Rendah`, currentRanking || '');
    
    if (ranking && ranking >= 1 && ranking <= 5) {
        fetch(`{{ route('sarpras.laporan.updateRanking', ':id') }}`.replace(':id', laporanId), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ranking: parseInt(ranking)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('danger', 'Gagal mengupdate ranking');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Terjadi kesalahan saat mengupdate ranking');
        });
    }
}

// Handle verifikasi form
document.getElementById('formVerifikasi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const laporanId = document.getElementById('laporanId').value;
    
    fetch(`{{ route('sarpras.laporan.verifikasi', ':id') }}`.replace(':id', laporanId), {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalVerifikasi')).hide();
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('danger', 'Gagal memverifikasi laporan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat memverifikasi laporan');
    });
});

// Batch update function
function batchUpdate() {
    const checkedBoxes = document.querySelectorAll('.laporan-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        showAlert('warning', 'Pilih minimal satu laporan untuk diupdate');
        return;
    }
    
    const status = prompt('Update status untuk ' + checkedBoxes.length + ' laporan:\n\n1. diproses\n2. diperbaiki\n3. selesai\n4. ditolak\n\nMasukkan pilihan (1-4):');
    
    const statusMap = {
        '1': 'diproses',
        '2': 'diperbaiki', 
        '3': 'selesai',
        '4': 'ditolak'
    };
    
    if (statusMap[status]) {
        const laporanIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        fetch('{{ route("sarpras.laporan.batchUpdate") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                laporan_ids: laporanIds,
                status: statusMap[status]
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('danger', 'Gagal mengupdate laporan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Terjadi kesalahan saat mengupdate laporan');
        });
    }
}

// Export function
function exportData() {
    const currentParams = new URLSearchParams(window.location.search);
    const exportUrl = '{{ route("sarpras.laporan.export") }}?' + currentParams.toString();
    
    window.open(exportUrl, '_blank');
}

// Helper functions
function getStatusBadge(status) {
    const badges = {
        'menunggu_verifikasi': 'warning',
        'diproses': 'info',
        'diperbaiki': 'primary',
        'selesai': 'success',
        'ditolak': 'danger'
    };
    return badges[status] || 'secondary';
}

function getRankingLabel(ranking) {
    const labels = {
        1: 'Sangat Tinggi',
        2: 'Tinggi', 
        3: 'Sedang',
        4: 'Rendah',
        5: 'Sangat Rendah'
    };
    return labels[ranking] || 'Unknown';
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at top of container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush