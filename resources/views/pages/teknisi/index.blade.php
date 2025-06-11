@extends('layouts.app')

@section('title', 'Daftar Tugas Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    @foreach(['tinggi', 'sedang', 'rendah'] as $priority)
        @if(isset($tugasByPriority[$priority]) && $tugasByPriority[$priority]->count() > 0)
            <div class="mb-10">
                <div class="flex items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-800">
                        Prioritas {{ ucfirst($priority) }}
                    </h2>
                    <span class="ml-2 px-3 py-1 text-sm rounded-full
                        @if($priority === 'tinggi') bg-red-100 text-red-800
                        @elseif($priority === 'sedang') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $tugasByPriority[$priority]->count() }} Tugas
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                        <thead class="bg-gray-50 text-sm text-gray-600 text-left">
                            <tr>
                                <th class="px-4 py-3">Fasilitas</th>
                                <th class="px-4 py-3">Lokasi</th>
                                <th class="px-4 py-3">Tanggal Laporan</th>
                                <th class="px-4 py-3">Batas Waktu</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($tugasByPriority[$priority] as $tugas)
                                <tr class="bg-white">
                                    <td class="px-4 py-2 font-medium text-gray-800">
                                        {{ $tugas->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $tugas->laporan->fasilitasRuang->ruang->nama_ruang }} - 
                                        {{ $tugas->laporan->fasilitasRuang->ruang->gedung->nama_gedung }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $tugas->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                        $batasWaktu = $tugas->batas_waktu;
                                        $now = \Carbon\Carbon::now();
                                        $label = '';
                                        $warna = 'text-gray-800';
                                        
                                        if ($tugas->is_overdue) {
                                            $label = 'Terlambat';
                                            $warna = 'text-red-600';
                                        } elseif ($batasWaktu && $now->diffInHours($batasWaktu, false) <= 24) {
                                            $label = 'Segera';
                                            $warna = 'text-yellow-600';
                                        } else {
                                            $label = 'Masih Ada Waktu';
                                            $warna = 'text-green-600';
                                        }
                                        @endphp
                                        
                                        <div class="flex flex-col">
                                            <span class="{{ $warna }}">
                                                {{ $batasWaktu ? $batasWaktu->format('d M Y, H:i') : '-' }}
                                            </span>
                                            @if($batasWaktu)
                                            <span class="text-xs {{ $warna }}">{{ $label }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($tugas->status === 'ditugaskan') bg-blue-100 text-blue-800
                                            @elseif($tugas->status === 'dikerjakan') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ $tugas->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <button 
                                            onclick="toggleDetail('detail-{{ $tugas->id }}')" 
                                            class="text-sm text-blue-600 hover:underline">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>

                                <!-- Baris Detail (Deskripsi & Form) -->
                                <tr id="detail-{{ $tugas->id }}" class="hidden bg-gray-50">
                                    <td colspan="6" class="px-4 py-4">
                                        <div class="space-y-4">
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Deskripsi Kerusakan:</p>
                                                <p class="text-gray-800">{{ $tugas->laporan->deskripsi }}</p>
                                            </div>

                                            <form action="{{ route('teknisi.updateTugas', $tugas->id) }}" method="POST" class="space-y-3">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="block text-sm text-gray-500 mb-1">Catatan Perbaikan</label>
                                                    <textarea name="catatan" rows="2" class="w-full border rounded text-sm" required>{{ old('catatan', $tugas->catatan) }}</textarea>
                                                </div>

                                                <div class="flex items-center gap-3">
                                                    <select name="status" class="w-48 border rounded text-sm" required>
                                                        <option value="ditugaskan" {{ $tugas->status == 'ditugaskan' ? 'selected' : '' }}>Menunggu</option>
                                                        <option value="dikerjakan" {{ $tugas->status == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                                                        <option value="selesai" {{ $tugas->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    @if($tugasByPriority->isEmpty())
        <div class="text-center py-8 bg-white rounded-lg shadow-sm">
            <p class="text-gray-500">Tidak ada tugas perbaikan yang aktif.</p>
        </div>
    @endif
</div>

<!-- Script Toggle -->
<script>
    function toggleDetail(id) {
        const row = document.getElementById(id);
        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    }
</script>
@endsection
