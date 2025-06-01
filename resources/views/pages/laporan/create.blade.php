<!-- Modal Tambah Laporan -->
<div id="addLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah Laporan</h3>
            <form id="addLaporanForm" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                        <textarea id="add_deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>

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
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
