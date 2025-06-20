<!-- Modal Tambah Laporan -->
<div id="addLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto h-full w-full">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-8 relative transition-all duration-200">
        <button type="button" class="close-modal absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        <h3 class="text-2xl font-bold mb-6 text-indigo-700 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Laporan
        </h3>
        <form id="addLaporanForm" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="step1" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="add_ruang_id" class="block text-sm font-medium text-gray-700">Ruang</label>
                        <select id="add_ruang_id" name="ruang_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Ruang</option>
                            @foreach(\App\Models\Ruang::all() as $ruang)
                                <option value="{{ $ruang->id_ruang }}">{{ $ruang->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="add_fasilitas_id" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <select id="add_fasilitas_id" name="fasilitas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Fasilitas</option>
                        </select>
                    </div>
                    <div>
                        <label for="add_id_fas_ruang" class="block text-sm font-medium text-gray-700">Kode Fasilitas</label>
                        <select id="add_id_fas_ruang" name="id_fas_ruang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih Kode Fasilitas</option>
                        </select>
                    </div>
                    <div>
                        <label for="add_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="add_deskripsi" name="deskripsi" rows="5" class="mt-1 block w-full rounded-lg border-gray-300 shadow-inner focus:border-blue-500 focus:ring-blue-500 resize-none" required></textarea>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="add_url_foto" class="block text-sm font-medium text-gray-700">
                            Foto <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="file"
                            id="add_url_foto"
                            name="url_foto"
                            class="mt-1 block w-full"
                            accept="image/*"
                            required
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            Wajib mengunggah foto kerusakan (Maks. 2MB)
                        </p>
                        <div id="add_photo_preview_container" class="mt-3 rounded-lg overflow-hidden">
                            <img id="add_photo_preview" src="#" alt="Preview Foto" class="shadow max-h-72 w-full object-contain hidden" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6" id="step1BtnGroup">
                <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="button" id="nextStepBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Next
                </button>
            </div>
            <div id="step2" class="space-y-6 hidden mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kerusakan</label>
                    <input type="range" min="1" max="5" value="3" id="tingkat_kerusakan_pelapor" name="tingkat_kerusakan_pelapor" class="w-full">
                    <div class="text-xs flex justify-between">
                        <span>Sedikit Rusak</span>
                        <span>Sangat Rusak</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dampak Akademik</label>
                    <input type="range" min="1" max="5" value="3" id="dampak_akademik_pelapor" name="dampak_akademik_pelapor" class="w-full">
                    <div class="text-xs flex justify-between">
                        <span>Sedikit Berdampak</span>
                        <span>Sangat Berdampak</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah yang Membutuhkan</label>
                    <input type="range" min="1" max="5" value="3" id="kebutuhan_pelapor" name="kebutuhan_pelapor" class="w-full">
                    <div class="text-xs flex justify-between">
                        <span>Sedikit Orang</span>
                        <span>Banyak Orang</span>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" id="prevStepBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Sebelumnya
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
