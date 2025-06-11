@extends('layouts.app')

@section('title', 'Daftar Tugas Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    @foreach(['tinggi', 'sedang', 'rendah'] as $priority)
        @if(isset($tugasByPriority[$priority]) && $tugasByPriority[$priority]->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Prioritas {{ ucfirst($priority) }}
                        </h2>
                        <span class="ml-3 px-3 py-1 text-sm font-medium rounded-full
                            @if($priority === 'tinggi') bg-red-100 text-red-800
                            @elseif($priority === 'sedang') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $tugasByPriority[$priority]->count() }} Tugas
                        </span>
                    </div>
                    
                    <button onclick="toggleAllDetails('{{ $priority }}')" 
                            class="text-sm text-gray-600 hover:text-gray-800 transition-colors">
                        <span id="toggle-text-{{ $priority }}">Buka Semua</span>
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fasilitas & Lokasi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tugasByPriority[$priority] as $tugas)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $tugas->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $tugas->laporan->fasilitasRuang->ruang->nama_ruang }} • 
                                                    {{ $tugas->laporan->fasilitasRuang->ruang->gedung->nama_gedung }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col space-y-1">
                                                <div class="text-sm text-gray-500">
                                                    Dilaporkan: {{ $tugas->created_at->format('d M Y, H:i') }}
                                                </div>
                                                @php
                                                $batasWaktu = $tugas->batas_waktu;
                                                $now = \Carbon\Carbon::now();
                                                $label = '';
                                                $warna = 'text-gray-600';
                                                
                                                if ($tugas->is_overdue) {
                                                    $label = 'Terlambat';
                                                    $warna = 'text-red-600 font-medium';
                                                } elseif ($batasWaktu && $now->diffInHours($batasWaktu, false) <= 24) {
                                                    $label = 'Segera';
                                                    $warna = 'text-yellow-600 font-medium';
                                                } else {
                                                    $label = 'Normal';
                                                    $warna = 'text-green-600';
                                                }
                                                @endphp
                                                
                                                @if($batasWaktu)
                                                <div class="text-sm {{ $warna }}">
                                                    Deadline: {{ $batasWaktu->format('d M Y, H:i') }}
                                                    <span class="ml-1 text-xs">({{ $label }})</span>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($tugas->status === 'ditugaskan') bg-blue-100 text-blue-800
                                                @elseif($tugas->status === 'dikerjakan') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $tugas->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button 
                                                onclick="toggleDetail('detail-{{ $tugas->id }}')" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-blue-700 hover:bg-blue-800 rounded transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Detail Row -->
                                    <tr id="detail-{{ $tugas->id }}" class="hidden detail-row" data-priority="{{ $priority }}">
                                        <td colspan="4" class="px-6 py-4 bg-gray-50 border-t">
                                            <div class="max-w-4xl space-y-4">
                                                <div class="bg-white p-4 rounded-lg border">
                                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Deskripsi Kerusakan</h4>
                                                    <p class="text-gray-700 leading-relaxed">{{ $tugas->laporan->deskripsi }}</p>
                                                </div>

                                                <div class="bg-white p-4 rounded-lg border">
                                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Update Status Perbaikan</h4>
                                                    <form action="{{ route('teknisi.updateTugas', $tugas->id) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <div>
                                                            <label for="catatan-{{ $tugas->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                                Catatan Perbaikan
                                                            </label>
                                                            <textarea 
                                                                id="catatan-{{ $tugas->id }}"
                                                                name="catatan" 
                                                                rows="3" 
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" 
                                                                placeholder="Masukkan catatan perbaikan..."
                                                                required>{{ old('catatan', $tugas->catatan) }}</textarea>
                                                        </div>

                                                        <div class="flex items-center gap-4">
                                                            <div class="flex-1">
                                                                <label for="status-{{ $tugas->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                                    Status
                                                                </label>
                                                                <select 
                                                                    id="status-{{ $tugas->id }}"
                                                                    name="status" 
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                                    required>
                                                                    <option value="ditugaskan" {{ $tugas->status == 'ditugaskan' ? 'selected' : '' }}>Menunggu</option>
                                                                    <option value="dikerjakan" {{ $tugas->status == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                                                                    <option value="selesai" {{ $tugas->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="flex gap-2 pt-6">
                                                                <button 
                                                                    type="submit" 
                                                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                                    Update
                                                                </button>
                                                                <button 
                                                                    type="button" 
                                                                    onclick="toggleDetail('detail-{{ $tugas->id }}')"
                                                                    class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                                                    Tutup
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if(collect($tugasByPriority)->flatten()->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada tugas</h3>
            <p class="text-gray-500">Saat ini tidak ada tugas perbaikan yang aktif.</p>
        </div>
    @endif
</div>

<script>
    function toggleDetail(id) {
        const row = document.getElementById(id);
        const arrow = document.querySelector(`[data-target="${id}"]`);
        
        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');
            if (arrow) arrow.classList.add('rotate-90');
        } else {
            row.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-90');
        }
    }

    function toggleAllDetails(priority) {
        const detailRows = document.querySelectorAll(`[data-priority="${priority}"]`);
        const toggleButton = document.getElementById(`toggle-text-${priority}`);
        const allHidden = Array.from(detailRows).every(row => row.classList.contains('hidden'));
        
        detailRows.forEach(row => {
            const id = row.id;
            const arrow = document.querySelector(`[data-target="${id}"]`);
            
            if (allHidden) {
                row.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-90');
            } else {
                row.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-90');
            }
        });
        
        toggleButton.textContent = allHidden ? 'Tutup Semua' : 'Buka Semua';
    }
</script>
@endsection