<!-- Modal Edit Laporan -->
<div id="editLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative mx-auto my-6 sm:my-10 p-0 w-11/12 max-w-2xl shadow-xl rounded-xl bg-white modal-content">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit text-blue-500 mr-2"></i> Edit Laporan
                </h3>
                <button type="button" class="close-modal text-gray-400 hover:text-gray-600 transition-colors p-2">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <form id="editLaporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Info Fasilitas -->
                        <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                            <div class="flex items-center text-sm text-blue-700 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>Informasi fasilitas tidak dapat diubah</span>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Ruang</label>
                                    <input type="text" id="edit_ruang_display" class="w-full p-2 bg-white border rounded-md text-gray-700 shadow-inner text-sm" readonly>
                                    <input type="hidden" id="edit_ruang_id" name="ruang_id">
                                </div>
            
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Fasilitas</label>
                                    <input type="text" id="edit_fasilitas_display" class="w-full p-2 bg-white border rounded-md text-gray-700 shadow-inner text-sm" readonly>
                                    <input type="hidden" id="edit_fasilitas_id" name="fasilitas_id">
                                </div>
            
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Kode Fasilitas</label>
                                    <input type="text" id="edit_fas_ruang_display" class="w-full p-2 bg-white border rounded-md font-mono text-gray-700 shadow-inner text-sm" readonly>
                                    <input type="hidden" id="edit_id_fas_ruang" name="id_fas_ruang">
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-file-alt text-gray-400 mr-1"></i> Deskripsi Kerusakan
                            </label>
                            <textarea 
                                id="edit_deskripsi" 
                                name="deskripsi" 
                                rows="5" 
                                class="w-full p-2 sm:p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all text-sm h-full" 
                                required
                                placeholder="Deskripsikan kerusakan secara detail..."></textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <label for="edit_url_foto" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-camera text-gray-400 mr-1"></i> Foto Kerusakan
                        </label>
                        
                        <!-- Current Photo -->
                        <div id="current_photo" class="bg-gray-50 p-3 rounded-lg border mb-3">
                            <p class="text-xs sm:text-sm text-gray-500 mb-2 flex items-center">
                                <i class="fas fa-image mr-1.5"></i> Foto Saat Ini:
                            </p>
                            <div class="flex justify-center bg-white p-2 rounded shadow-sm h-48 sm:h-60">
                                <img id="edit_photo_preview" src="" alt="Foto Current" class="max-h-full max-w-full rounded object-contain">
                            </div>
                        </div>
                        
                        <!-- Upload New Photo -->
                        <div class="flex items-center justify-center w-full">
                            <label for="edit_url_foto" class="flex flex-col items-center justify-center w-full h-24 sm:h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-3 pb-3 px-2 text-center">
                                    <i class="fas fa-cloud-upload-alt mb-2 text-gray-400 text-lg sm:text-xl"></i>
                                    <p class="mb-1 text-xs sm:text-sm text-gray-500"><span class="font-semibold">Klik untuk upload foto baru</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau JPEG</p>
                                </div>
                                <input id="edit_url_foto" type="file" name="url_foto" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex flex-wrap sm:flex-nowrap justify-end gap-2 sm:space-x-3 pt-4 mt-4 border-t">
                    <button type="button" class="close-modal w-full sm:w-auto order-2 sm:order-1 btn-secondary">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <button type="submit" class="w-full sm:w-auto order-1 sm:order-2 btn-primary">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview new image when selected
    document.getElementById('edit_url_foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = document.getElementById('edit_photo_preview');
            const currentPhotoDiv = document.getElementById('current_photo');
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                currentPhotoDiv.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Add loading state on form submit
    document.getElementById('editLaporanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        this.submit();
    });
    
    // Ensure modal can be scrolled on mobile
    document.getElementById('editLaporanModal').addEventListener('touchmove', function(e) {
        e.stopPropagation();
    }, { passive: true });
</script>