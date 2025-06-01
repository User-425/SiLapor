@extends('layouts.app')

@section('title', 'Lapor Cepat')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Lapor Kerusakan</h2>
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <!-- Facility Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Fasilitas:</p>
                        <p class="font-medium">{{ $fasRuang->fasilitas->nama_fasilitas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kode Unit:</p>
                        <p class="font-medium">{{ $fasRuang->kode_fasilitas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Gedung:</p>
                        <p class="font-medium">{{ $fasRuang->ruang->gedung->nama_gedung }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ruangan:</p>
                        <p class="font-medium">{{ $fasRuang->ruang->nama_ruang }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_fas_ruang" value="{{ $fasRuang->id_fas_ruang }}">
                
                <div class="space-y-6">
                    <!-- Description Field -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi Kerusakan
                        </label>
                        <textarea 
                            id="deskripsi" 
                            name="deskripsi" 
                            rows="4" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Photo Upload Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Foto Kerusakan
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="url_foto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload foto</span>
                                        <input id="url_foto" name="url_foto" type="file" class="sr-only" required accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, JPEG up to 2MB
                                </p>
                            </div>
                        </div>
                        <div id="image-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="w-full max-h-48 object-contain">
                        </div>
                    </div>

                    <!-- Kriteria Laporan Fields -->
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Penilaian Kerusakan</h3>
                        
                        <div class="space-y-6">
                            <!-- Tingkat Kerusakan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kerusakan</label>
                                <input 
                                    type="range" 
                                    min="1" 
                                    max="5" 
                                    value="3" 
                                    id="tingkat_kerusakan_pelapor" 
                                    name="tingkat_kerusakan_pelapor" 
                                    class="w-full"
                                >
                                <div class="text-xs flex justify-between mt-1">
                                    <span>Sedikit Rusak</span>
                                    <span>Sangat Rusak</span>
                                </div>
                                <div class="text-center text-sm font-medium mt-1 hidden" id="tingkat-value">3</div>
                            </div>

                            <!-- Dampak Akademik -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dampak Akademik</label>
                                <input 
                                    type="range" 
                                    min="1" 
                                    max="5" 
                                    value="3" 
                                    id="dampak_akademik_pelapor" 
                                    name="dampak_akademik_pelapor" 
                                    class="w-full"
                                >
                                <div class="text-xs flex justify-between mt-1">
                                    <span>Sedikit Berdampak</span>
                                    <span>Sangat Berdampak</span>
                                </div>
                                <div class="text-center text-sm font-medium mt-1 hidden" id="dampak-value">3</div>
                            </div>

                            <!-- Jumlah yang Membutuhkan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah yang Membutuhkan</label>
                                <input 
                                    type="range" 
                                    min="1" 
                                    max="5" 
                                    value="3" 
                                    id="kebutuhan_pelapor" 
                                    name="kebutuhan_pelapor" 
                                    class="w-full"
                                >
                                <div class="text-xs flex justify-between mt-1">
                                    <span>Sedikit Orang</span>
                                    <span>Banyak Orang</span>
                                </div>
                                <div class="text-center text-sm font-medium mt-1 hidden" id="kebutuhan-value">3</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <a href="{{ url()->previous() }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Kirim Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('url_foto').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const img = preview.querySelector('img');
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });

    // Display current value of sliders
    document.getElementById('tingkat_kerusakan_pelapor').addEventListener('input', function(e) {
        document.getElementById('tingkat-value').textContent = this.value;
    });
    
    document.getElementById('dampak_akademik_pelapor').addEventListener('input', function(e) {
        document.getElementById('dampak-value').textContent = this.value;
    });
    
    document.getElementById('kebutuhan_pelapor').addEventListener('input', function(e) {
        document.getElementById('kebutuhan-value').textContent = this.value;
    });
</script>
@endpush
@endsection