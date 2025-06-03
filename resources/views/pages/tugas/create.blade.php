@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Tugaskan Teknisi</h1>
            <p class="text-gray-600 mt-2">Menugaskan teknisi untuk menangani laporan kerusakan</p>
        </div>

        <!-- Alert jika ada error -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda:
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('tugas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">

                <!-- Section A: Detail Laporan (Readonly) -->
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Detail Laporan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ID Laporan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Laporan</label>
                            <div class="bg-white p-3 rounded-md border border-gray-200 font-mono">
                                #{{ $laporan->id_laporan }}
                            </div>
                        </div>

                        <!-- Pelapor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pelapor</label>
                            <div class="bg-white p-3 rounded-md border border-gray-200">
                                {{ $laporan->pengguna->nama_pengguna ?? 'Tidak diketahui' }}
                                <div class="text-sm text-gray-500">{{ $laporan->pengguna->email ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Fasilitas Ruangan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Ruangan</label>
                            <div class="bg-white p-3 rounded-md border border-gray-200">
                                {{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? 'N/A' }}
                                <div class="text-sm text-gray-500">
                                    {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? 'N/A' }} - 
                                    {{ $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Waktu Pelaporan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Pelaporan</label>
                            <div class="bg-white p-3 rounded-md border border-gray-200">
                                {{ \Carbon\Carbon::parse($laporan->created_at)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kerusakan</label>
                        <div class="bg-white p-3 rounded-md border border-gray-200 min-h-[80px]">
                            {{ $laporan->deskripsi }}
                        </div>
                    </div>

                    <!-- Foto (jika ada) -->
                    @if($laporan->url_foto)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Kerusakan</label>
                        <div class="bg-white p-3 rounded-md border border-gray-200">
                            <img src="{{ $laporan->url_foto }}" alt="Foto kerusakan" class="max-w-xs h-auto rounded-lg shadow-sm">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Section A.5: Ranking Urgensi (Prominent Display) -->
                @if(isset($laporan->ranking))
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">🏆 Ranking Prioritas</h3>
                            <p class="text-sm text-gray-600">Berdasarkan sistem penilaian otomatis</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border-2
                                @if($laporan->ranking <= 3)
                                    bg-red-100 text-red-800 border-red-200
                                @elseif($laporan->ranking <= 7)
                                    bg-amber-100 text-amber-800 border-amber-200
                                @else
                                    bg-emerald-100 text-emerald-800 border-emerald-200
                                @endif
                            ">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                Prioritas #{{ $laporan->ranking }}
                                @if($laporan->ranking <= 3)
                                    (TINGGI)
                                @elseif($laporan->ranking <= 7)
                                    (SEDANG)
                                @else
                                    (RENDAH)
                                @endif
                            </span>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($laporan->ranking <= 3)
                                    Segera tangani!
                                @elseif($laporan->ranking <= 7)
                                    Perlu perhatian
                                @else
                                    Dapat dijadwalkan
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section B: Form Penugasan -->
                <div class="px-6 py-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">🔧 Form Penugasan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pilih Teknisi -->
                        <div>
                            <label for="id_pengguna" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Teknisi <span class="text-red-500">*</span>
                            </label>
                            <select name="id_pengguna" id="id_pengguna" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    required>
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach($teknisi as $tech)
                                    <option value="{{ $tech->id_pengguna }}" {{ old('id_pengguna') == $tech->id_pengguna ? 'selected' : '' }}>
                                        {{ $tech->nama }} - {{ $tech->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pengguna')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prioritas -->
                        <div>
                            <label for="prioritas" class="block text-sm font-medium text-gray-700 mb-2">
                                Prioritas <span class="text-red-500">*</span>
                            </label>
                            <select name="prioritas" id="prioritas" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    required>
                                <option value="">-- Pilih Prioritas --</option>
                                <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>
                                    🟢 Rendah
                                </option>
                                <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>
                                    🟡 Sedang
                                </option>
                                <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>
                                    🔴 Tinggi
                                </option>
                            </select>
                            @error('prioritas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Batas Waktu -->
                        <div class="md:col-span-2">
                            <label for="batas_waktu" class="block text-sm font-medium text-gray-700 mb-2">
                                Batas Waktu Pengerjaan
                            </label>
                            <input type="datetime-local"
                                name="batas_waktu"
                                id="batas_waktu"
                                value="{{ old('batas_waktu') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('batas_waktu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batas waktu khusus</p>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const now = new Date();
                            const year = now.getFullYear();
                            const month = String(now.getMonth() + 1).padStart(2, '0');
                            const day = String(now.getDate()).padStart(2, '0');
                            const hours = String(now.getHours()).padStart(2, '0');
                            const minutes = String(now.getMinutes()).padStart(2, '0');
                            
                            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                            document.getElementById('batas_waktu').min = minDateTime;
                        });
                        </script>

                        <!-- Catatan Tambahan -->
                        <div class="md:col-span-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Tambahan (Opsional)
                            </label>
                            <textarea name="catatan" 
                                      id="catatan" 
                                      rows="4" 
                                      placeholder="Contoh: Perlu tangga panjang, koordinasi dengan petugas keamanan, dll."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section C: Tombol Aksi -->
                <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
                    <a href="{{ route('tugas.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Tugaskan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
