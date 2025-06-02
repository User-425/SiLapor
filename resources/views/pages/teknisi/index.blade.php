@extends('layouts.app')

@section('title', 'Daftar Tugas Perbaikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Daftar Tugas Perbaikan</h1>
        <p class="text-gray-600 mt-1">Kelola dan perbarui status tugas perbaikan fasilitas</p>
    </div>

    <!-- Priority Sections -->
    @foreach(['tinggi', 'sedang', 'rendah'] as $priority)
        @if(isset($tugasByPriority[$priority]) && $tugasByPriority[$priority]->count() > 0)
            <div class="mb-8">
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

                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($tugasByPriority[$priority] as $tugas)
                        <div class="bg-white rounded-lg shadow-sm border">
                            <!-- Header -->
                            <div class="p-4 border-b">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-800">
                                            {{ $tugas->laporan->fasilitasRuang->fasilitas->nama_fasilitas }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $tugas->laporan->fasilitasRuang->ruang->nama_ruang }} - 
                                            {{ $tugas->laporan->fasilitasRuang->ruang->gedung->nama_gedung }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-sm rounded-full
                                        @if($tugas->status === 'ditugaskan') bg-blue-100 text-blue-800
                                        @elseif($tugas->status === 'dikerjakan') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $tugas->status_label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4 space-y-4">
                                <!-- Informasi Laporan -->
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">Tanggal Laporan</p>
                                        <p class="font-medium">{{ $tugas->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Batas Waktu</p>
                                        <p class="font-medium {{ $tugas->is_overdue ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $tugas->batas_waktu ? $tugas->batas_waktu->format('d M Y, H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Deskripsi Kerusakan -->
                                <div>
                                    <p class="text-gray-500 text-sm">Deskripsi Kerusakan</p>
                                    <p class="mt-1">{{ $tugas->laporan->deskripsi }}</p>
                                </div>

                                <!-- Update Form -->
<form action="{{ route('teknisi.updateTugas', $tugas->id) }}" 
      method="POST" 
      class="space-y-3">
    @csrf
    @method('PUT')
    
    <div>
        <label class="block text-sm text-gray-500 mb-1">Catatan Perbaikan</label>
        <textarea name="catatan" 
                  rows="2" 
                  class="w-full rounded border-gray-300 text-sm"
                  required>{{ old('catatan', $tugas->catatan) }}</textarea>
    </div>

    <div class="flex items-center space-x-4">
        <div class="flex-1">
            <select name="status" 
                    class="w-full rounded border-gray-300 text-sm"
                    required>
                <option value="ditugaskan" {{ $tugas->status == 'ditugaskan' ? 'selected' : '' }}>
                    Menunggu Pengerjaan
                </option>
                <option value="dikerjakan" {{ $tugas->status == 'dikerjakan' ? 'selected' : '' }}>
                    Sedang Dikerjakan
                </option>
                <option value="selesai" {{ $tugas->status == 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>
        </div>
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 focus:ring-2">
            Update
        </button>
    </div>
</form>
                            </div>
                        </div>
                    @endforeach
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
@endsection