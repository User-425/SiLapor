@extends('layouts.app')

@section('title', 'Kelola Laporan Kerusakan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Kelola Laporan Kerusakan</h1>
                    <p class="text-muted">Kelola dan verifikasi laporan kerusakan fasilitas</p>
                </div>
                <div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Menunggu Verifikasi</h6>
                            <h4 class="mb-0">{{ $statistik['menunggu_verifikasi'] }}</h4>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Diproses</h6>
                            <h4 class="mb-0">{{ $statistik['diproses'] }}</h4>
                        </div>
                        <i class="fas fa-cog fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Diperbaiki</h6>
                            <h4 class="mb-0">{{ $statistik['diperbaiki'] }}</h4>
                        </div>
                        <i class="fas fa-tools fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Selesai</h6>
                            <h4 class="mb-0">{{ $statistik['selesai'] }}</h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Ditolak</h6>
                            <h4 class="mb-0">{{ $statistik['ditolak'] }}</h4>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.laporan.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="menunggu_verifikasi" {{ $status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="diperbaiki" {{ $status == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ranking</label>
                        <select name="ranking" class="form-select">
                            <option value="">Semua Ranking</option>
                            <option value="1" {{ request('ranking') == '1' ? 'selected' : '' }}>1 ★</option>
                            <option value="2" {{ request('ranking') == '2' ? 'selected' : '' }}>2 ★</option>
                            <option value="3" {{ request('ranking') == '3' ? 'selected' : '' }}>3 ★</option>
                            <option value="4" {{ request('ranking') == '4' ? 'selected' : '' }}>4 ★</option>
                            <option value="5" {{ request('ranking') == '5' ? 'selected' : '' }}>5 ★</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('sarpras.laporan.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Laporan ({{ $laporans->total() }} total)</h6>
                <div>
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleBatchActions()">
                        <i class="fas fa-tasks"></i> Batch Actions
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Batch Actions (hidden by default) -->
            <div id="batchActions" class="p-3 border-bottom bg-light" style="display: none;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <select id="batchStatus" class="form-select">
                                <option value="">Pilih Status</option>
                                <option value="diproses">Diproses</option>
                                <option value="diperbaiki">Diperbaiki</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            <button class="btn btn-primary" onclick="batchUpdateStatus()">
                                Update Status Terpilih
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <span id="selectedCount" class="text-muted">0 item terpilih</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="checkAll" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Pelapor</th>
                            <th>Fasilitas</th>
                            <th>Ruang</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Ranking</th>
                            <th>Tanggal</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporans as $laporan)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input laporan-check" value="{{ $laporan->id_laporan }}">
                            </td>
                            <td>
                                <span class="badge bg-secondary">#{{ $laporan->id_laporan }}</span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $laporan->pengguna->nama ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $laporan->pengguna->email ?? '' }}</small>
                                </div>
                            </td>
                            <td>{{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                            <td>{{ $laporan->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }}</td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $laporan->deskripsi }}">
                                    {{ $laporan->deskripsi }}
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $laporan->status_badge_class }}">
                                    {{ $laporan->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($laporan->ranking)
                                    <span title="Rating {{ $laporan->ranking }}/5">
                                        {!! $laporan->ranking_stars !!}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $laporan->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="showDetail({{ $laporan->id_laporan }})" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($laporan->status == 'menunggu_verifikasi')
                                    <button class="btn btn-outline-success" onclick="showVerifikasi({{ $laporan->id_laporan }})" title="Verifikasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-outline-warning" onclick="showUpdateStatus({{ $laporan->id_laporan }})" title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Tidak ada laporan ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($laporans->hasPages())
        <div class="card-footer">
            {{ $laporans->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Laporan -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
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
<div class="modal fade" id="verifikasiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="verifikasiForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="diproses">Terima dan Proses</option>
                            <option value="selesai">Langsung Selesaikan</option>
                            <option value="ditolak">Tolak Laporan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ranking Prioritas</label>
                        <select name="ranking" class="form-select">
                            <option value="">Pilih Ranking</option>
                            <option value="1">1 ★ - Rendah</option>
                            <option value="2">2 ★ - Cukup</option>
                            <option value="3">3 ★ - Sedang</option>
                            <option value="4">4 ★ - Tinggi</option>
                            <option value="5">5 ★ - Sangat Tinggi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateStatusForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="diproses">Diproses</option>
                            <option value="diperbaiki">Diperbaiki</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ranking Prioritas</label>
                        <select name="ranking" class="form-select">
                            <option value="">Pilih Ranking</option>
                            <option value="1">1 ★ - Rendah</option>
                            <option value="2">2 ★ - Cukup</option>
                            <option value="3">3 ★ - Sedang</option>
                            <option value="4">4 ★ - Tinggi</option>
                            <option value="5">5 ★ - Sangat Tinggi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Export -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sarpras.laporan.export') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title">Export Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Format Export</label>
                        <select name="format" class="form-select">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="semua">Semua Status</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="diproses">Diproses</option>
                            <option value="diperbaiki">Diperbaiki</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentLaporanId = null;

// Show detail laporan
function showDetail(id) {
    currentLaporanId = id;
    
    // Reset modal content
    document.getElementById('detailContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('detailModal')).show();
    
    // Fetch detail
    fetch(`{{ route('sarpras.laporan.index') }}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('detailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informasi Laporan</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%">ID Laporan</td>
                            <td><strong>#${data.id_laporan}</strong></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span class="badge bg-primary">${data.status}</span></td>
                        </tr>
                        <tr>
                            <td>Ranking</td>
                            <td>${data.ranking ? '★'.repeat(data.ranking) + '☆'.repeat(5-data.ranking) : '-'}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lapor</td>
                            <td>${data.created_at}</td>
                        </tr>
                        <tr>
                            <td>Update Terakhir</td>
                            <td>${data.updated_at}</td>
                        </tr>
                    </table>
                    
                    <h6 class="mt-3">Informasi Pelapor</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%">Nama</td>
                            <td>${data.pengguna.nama}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>${data.pengguna.email}</td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td>${data.pengguna.role}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Informasi Fasilitas</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%">Fasilitas</td>
                            <td>${data.fasilitasRuang.fasilitas.nama_fasilitas}</td>
                        </tr>
                        <tr>
                            <td>Ruang</td>
                            <td>${data.fasilitasRuang.ruang.nama_ruang}</td>
                        </tr>
                        <tr>
                            <td>Kode</td>
                            <td>${data.fasilitasRuang.kode_fasilitas}</td>
                        </tr>
                    </table>
                    
                    <h6 class="mt-3">Deskripsi Kerusakan</h6>
                    <p class="text-muted">${data.deskripsi}</p>
                    
                    ${data.url_foto ? `
                    <h6>Foto Kerusakan</h6>
                    <img src="${data.url_foto}" class="img-fluid rounded" alt="Foto Kerusakan">
                    ` : ''}
                </div>
            </div>
        `;
    })
    .catch(error => {
        document.getElementById('detailContent').innerHTML = `
            <div class="alert alert-danger">
                Error loading detail: ${error.message}
            </div>
        `;
    });
}

// Show verifikasi modal
function showVerifikasi(id) {
    currentLaporanId = id;
    new bootstrap.Modal(document.getElementById('verifikasiModal')).show();
}

// Show update status modal
function showUpdateStatus(id) {
    currentLaporanId = id;
    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
}

// Handle verifikasi form
document.getElementById('verifika