<!-- Modal Edit Laporan -->
<div id="editLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Laporan</h3>
            <form id="editLaporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_ruang_id" class="block text-sm font-medium text-gray-700">Ruang</label>
                        <input type="text" id="edit_ruang_display" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                        <input type="hidden" id="edit_ruang_id" name="ruang_id">
                    </div>

                    <div>
                        <label for="edit_fasilitas_id" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <input type="text" id="edit_fasilitas_display" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                        <input type="hidden" id="edit_fasilitas_id" name="fasilitas_id">
                    </div>

                    <div>
                        <label for="edit_id_fas_ruang" class="block text-sm font-medium text-gray-700">Kode Fasilitas</label>
                        <input type="text" id="edit_fas_ruang_display" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100" readonly>
                        <input type="hidden" id="edit_id_fas_ruang" name="id_fas_ruang">
                    </div>

                    <div>
                        <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>

                    <div>
                        <label for="edit_url_foto" class="block text-sm font-medium text-gray-700">Foto Baru (opsional)</label>
                        <input type="file" id="edit_url_foto" name="url_foto" class="mt-1 block w-full" accept="image/*">
                        <div id="current_photo" class="mt-2">
                            <p class="text-sm text-gray-500">Foto Saat Ini:</p>
                            <img id="edit_photo_preview" src="" alt="Foto Current" class="mt-2 max-h-40 rounded shadow">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
