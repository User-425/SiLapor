@extends('layouts.app')

@section('title', 'Daftar Laporan Kerusakan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Alert Success -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-gray-700 px-4 py-3 rounded mb-6 relative">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    <div id="ajax-alert" class="hidden bg-green-100 border border-green-400 text-gray-700 px-4 py-3 rounded mb-6 relative"></div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">
                    Daftar Laporan ({{ $laporans->total() ?? $laporans->count() }} total)
                </h2>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($laporan->status === 'menunggu_verifikasi' && Auth::user()->peran === 'sarpras')
                                    <form action="{{ route('laporan.verifikasi', $laporan->id_laporan) }}" method="POST" class="inline verify-form">
                                        @csrf
                                        <select name="status" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                                            <option value="" disabled selected>Menunggu Verifikasi</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge_class }}">
                                        {{ $laporan->status_label }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $laporan->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- Tombol Detail (Mata) -->
                                    <button onclick="showDetail({{ $laporan->id_laporan }})"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200"
                                            title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <!-- Tombol Edit -->
                                    <button type="button"
                                        class="edit-sarpras-btn text-yellow-600 hover:text-yellow-900 transition duration-200"
                                        data-id="{{ $laporan->id_laporan }}"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <!-- Tombol Delete -->
                                    <form action="{{ route('laporan.destroy', $laporan->id_laporan) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Yakin hapus laporan ini?')">
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
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg">Tidak ada laporan ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($laporans, 'hasPages') && $laporans->hasPages())
            <div class="bg-white px-6 py-3 border-t border-gray-200">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>

    <!-- Modal untuk Detail -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <h2 class="text-lg font-semibold mb-4">Detail Laporan</h2>
            <div id="detailContent" class="space-y-4 max-h-[80vh] overflow-y-auto">
                <!-- Konten akan diisi via AJAX -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Tutup</button>
            </div>
        </div>
    </div>

    @include('pages.laporan.sarpras-edit')
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJAX untuk Detail
    function showDetail(id) {
        fetch('{{ url('laporan/detail') }}/' + id, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="space-y-2">
                    <p><strong>ID Laporan:</strong> ${data.id_laporan}</p>
                    <p><strong>Pelapor:</strong> ${data.pengguna.nama}</p>
                    <p><strong>Email:</strong> ${data.pengguna.email}</p>
                    <p><strong>Fasilitas:</strong> ${data.fasilitasRuang.fasilitas.nama_fasilitas}</p>
                    <p><strong>Ruang:</strong> ${data.fasilitasRuang.ruang.nama_ruang}</p>
                    <p><strong>Gedung:</strong> ${data.fasilitasRuang.ruang.gedung.nama_gedung}</p>
                    <p><strong>Status:</strong> ${data.status.replace('_', ' ').toUpperCase()}</p>
                    <p><strong>Tanggal Dibuat:</strong> ${data.created_at}</p>
                    <p class="mt-4"><strong>Deskripsi:</strong></p>
                    <div class="border border-gray-300 rounded-md p-4 bg-gray-50 text-sm text-gray-900 max-h-40 overflow-y-auto">
                        ${data.deskripsi}
                    </div>
                    ${data.url_foto ? `
                    <p class="mt-4"><strong>Foto:</strong></p>
                    <img src="${data.url_foto}" alt="Foto Kerusakan" class="max-w-xs max-h-64 mt-2 object-contain">
                    ` : ''}
                </div>
            `;
            document.getElementById('detailContent').innerHTML = content;
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal memuat detail laporan.'
            });
        });
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('detailContent').innerHTML = '';
    }

    // AJAX untuk Verifikasi
    document.querySelectorAll('.verify-form select[name="status"]').forEach(select => {
        select.onchange = function() {
            this.form.requestSubmit(); // submit form via JS
        };
    });

    // Edit Laporan Sarpras
    document.querySelectorAll('.edit-sarpras-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            const response = await fetch(`/laporan/detail/${id}`);
            const data = await response.json();

            // Set action form dengan id laporan
            document.getElementById('sarprasEditLaporanForm').action = `/laporan/${id}/update-sarpras`;

            // Isi field id_fas_ruang
            document.getElementById('edit_id_fas_ruang').value = data.fasilitasRuang?.id_fas_ruang || '';

            // Isi field lain seperti sebelumnya...
            document.getElementById('sarprasEditLaporanId').value = data.id_laporan;
            document.getElementById('edit_deskripsi').value = data.deskripsi || '';
            document.getElementById('tingkat_kerusakan_sarpras').value = data.kriteria?.tingkat_kerusakan_sarpras ?? 3;
            document.getElementById('dampak_akademik_sarpras').value = data.kriteria?.dampak_akademik_sarpras ?? 3;
            document.getElementById('kebutuhan_sarpras').value = data.kriteria?.kebutuhan_sarpras ?? 3;
            // Foto preview
            if (data.url_foto) {
                document.getElementById('edit_photo_preview').src = data.url_foto;
                document.getElementById('current_photo').style.display = 'block';
            } else {
                document.getElementById('current_photo').style.display = 'none';
            }
            document.getElementById('foto_lama').value = data.url_foto || '';

            // Tampilkan modal
            document.getElementById('sarprasEditLaporanModal').classList.remove('hidden');
        });
    });

    // Tutup modal
    document.querySelectorAll('#sarprasEditLaporanModal .close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('sarprasEditLaporanModal').classList.add('hidden');
            document.getElementById('sarprasEditLaporanForm').reset();
        });
    });

    document.getElementById('sarprasEditLaporanForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const url = form.action;
        const formData = new FormData(form);

        try {
            const response = await fetch(url, {
                method: 'POST', // Laravel expects POST + _method=PUT
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('sarprasEditLaporanModal').classList.add('hidden');
                // Tampilkan alert di atas tabel
                const alertBox = document.getElementById('ajax-alert');
                alertBox.textContent = data.message || 'Data berhasil diupdate!';
                alertBox.classList.remove('hidden');
                alertBox.classList.remove('bg-red-100', 'border-red-400');
                alertBox.classList.add('bg-green-100', 'border-green-400');
                // Sembunyikan alert setelah 2 detik
                setTimeout(() => {
                    alertBox.classList.add('hidden');
                }, 2000);
                // Optional: reload table/list, atau update row secara dinamis
            } else {
                const alertBox = document.getElementById('ajax-alert');
                alertBox.textContent = data.message || 'Gagal mengupdate data.';
                alertBox.classList.remove('hidden');
                alertBox.classList.remove('bg-green-100', 'border-green-400');
                alertBox.classList.add('bg-red-100', 'border-red-400');
                setTimeout(() => {
                    alertBox.classList.add('hidden');
                }, 3000);
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat mengupdate data.'
            });
        }
    });
</script>
@endsection
@endsection
