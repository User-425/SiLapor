<div id="sarprasEditLaporanModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto my-4 sm:my-8 w-11/12 max-w-4xl shadow-xl rounded-xl bg-white modal-content">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4 sm:mb-5">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-tools text-blue-500 mr-2"></i> Edit Laporan (Sarpras)
                </h3>
                <button type="button" class="close-modal text-gray-400 hover:text-gray-600 transition-colors p-2">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form id="sarprasEditLaporanForm" action="" method="POST" enctype="multipart/form-data" class="max-h-[80vh] overflow-y-auto px-1">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_laporan" id="sarprasEditLaporanId">
                <input type="hidden" name="id_fas_ruang" id="edit_id_fas_ruang">
                <input type="hidden" name="foto_lama" id="foto_lama">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Left Column - Facility Info & Description -->
                    <div class="lg:col-span-2 space-y-4">
                        <!-- Informasi Fasilitas Section -->
                        <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                            <div class="flex items-center text-xs sm:text-sm text-blue-700 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>Informasi Fasilitas</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Ruang</label>
                                    <div id="sarpras_edit_ruang" class="font-semibold text-gray-700 text-sm">-</div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Fasilitas</label>
                                    <div id="sarpras_edit_fasilitas" class="font-semibold text-gray-700 text-sm">-</div>
                                </div>
                                
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Kode Fasilitas</label>
                                    <div id="sarpras_edit_kode" class="font-mono bg-white px-2 py-1 rounded border inline-block text-xs sm:text-sm">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg">
                            <div class="flex items-center text-xs sm:text-sm text-yellow-700 mb-2">
                                <i class="fas fa-tasks mr-2"></i>
                                <span>Status Laporan</span>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div>
                                    <label for="edit_status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                    <select id="edit_status" name="status" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" required>
                                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                        <option value="diproses">Diproses</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i> Mengubah status ke "Diproses" mengharuskan pengisian kriteria penilaian.
                                    </p>
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
                                rows="6" 
                                class="w-full p-2 sm:p-3 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all text-sm" 
                                required
                                placeholder="Deskripsikan kerusakan secara detail..."></textarea>
                        </div>

                        <!-- Penilaian Section - For Mobile View -->
                        <div class="pt-4 border-t block lg:hidden">
                            <h4 class="text-xs sm:text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-chart-bar text-blue-500 mr-1.5"></i> Penilaian Kerusakan
                            </h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Tingkat Kerusakan -->
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-xs sm:text-sm font-medium text-gray-700">Tingkat Kerusakan</label>
                                        <span id="kerusakan_value_mobile" class="text-xs font-semibold px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full">3/5</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-wrench text-gray-300 text-xs sm:text-base"></i>
                                        <input type="range" min="1" max="5" value="3" id="tingkat_kerusakan_sarpras_mobile" name="tingkat_kerusakan_sarpras" 
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-orange-500 touch-manipulation"
                                            oninput="document.getElementById('kerusakan_value_mobile').textContent = this.value + '/5';
                                                    document.getElementById('kerusakan_value').textContent = this.value + '/5';
                                                    document.getElementById('tingkat_kerusakan_sarpras').value = this.value;">
                                        <i class="fas fa-tools text-orange-500 text-xs sm:text-base"></i>
                                    </div>
                                </div>
                                
                                <!-- Dampak Akademik -->
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-xs sm:text-sm font-medium text-gray-700">Dampak Akademik</label>
                                        <span id="dampak_value_mobile" class="text-xs font-semibold px-2 py-0.5 bg-red-100 text-red-700 rounded-full">3/5</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-book text-gray-300 text-xs sm:text-base"></i>
                                        <input type="range" min="1" max="5" value="3" id="dampak_akademik_sarpras_mobile" name="dampak_akademik_sarpras" 
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-500 touch-manipulation"
                                            oninput="document.getElementById('dampak_value_mobile').textContent = this.value + '/5';
                                                    document.getElementById('dampak_value').textContent = this.value + '/5';
                                                    document.getElementById('dampak_akademik_sarpras').value = this.value;">
                                        <i class="fas fa-book-open text-red-500 text-xs sm:text-base"></i>
                                    </div>
                                </div>
                                
                                <!-- Jumlah yang Membutuhkan -->
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Membutuhkan</label>
                                        <span id="jumlah_value_mobile" class="text-xs font-semibold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">3/5</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-user text-gray-300 text-xs sm:text-base"></i>
                                        <input type="range" min="1" max="5" value="3" id="kebutuhan_sarpras_mobile" name="kebutuhan_sarpras" 
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500 touch-manipulation"
                                            oninput="document.getElementById('jumlah_value_mobile').textContent = this.value + '/5';
                                                    document.getElementById('jumlah_value').textContent = this.value + '/5';
                                                    document.getElementById('kebutuhan_sarpras').value = this.value;">
                                        <i class="fas fa-users text-blue-500 text-xs sm:text-base"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Photo & Assessment -->
                    <div class="space-y-4">
                        <!-- Foto -->
                        <div>
                            <label for="edit_url_foto" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-camera text-gray-400 mr-1"></i> Foto Kerusakan
                            </label>
                            
                            <!-- Current Photo -->
                            <div id="current_photo" class="bg-gray-50 p-2 sm:p-3 rounded-lg border mb-3">
                                <div class="flex justify-center bg-white p-1 rounded shadow-sm h-40 sm:h-48">
                                    <img id="edit_photo_preview" src="" alt="Foto Kerusakan" class="max-h-full max-w-full rounded object-contain">
                                </div>
                            </div>
                            
                            <!-- Upload New Photo -->
                            <div class="flex items-center justify-center w-full">
                                <label for="edit_url_foto" class="flex flex-col items-center justify-center w-full h-20 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-2 pb-2 px-2 text-center">
                                        <i class="fas fa-cloud-upload-alt mb-1 text-gray-400 text-lg"></i>
                                        <p class="text-xs sm:text-sm text-gray-500"><span class="font-semibold">Klik untuk upload foto baru</span></p>
                                    </div>
                                    <input id="edit_url_foto" type="file" name="url_foto" class="hidden" accept="image/*" />
                                </label>
                            </div>
                        </div>

                        <!-- Penilaian Section - For Desktop View -->
                        <div class="pt-2 hidden lg:block">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-chart-bar text-blue-500 mr-1.5"></i> Penilaian Kerusakan
                            </h4>
                            
                            <!-- Tingkat Kerusakan -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="text-xs sm:text-sm font-medium text-gray-700">Tingkat Kerusakan</label>
                                    <span id="kerusakan_value" class="text-xs font-semibold px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full">3/5</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-wrench text-gray-300"></i>
                                    <input type="range" min="1" max="5" value="3" id="tingkat_kerusakan_sarpras" name="tingkat_kerusakan_sarpras" 
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-orange-500"
                                        oninput="document.getElementById('kerusakan_value').textContent = this.value + '/5';
                                                document.getElementById('kerusakan_value_mobile').textContent = this.value + '/5';
                                                document.getElementById('tingkat_kerusakan_mobile').value = this.value;">
                                    <i class="fas fa-tools text-orange-500"></i>
                                </div>
                                <div class="text-xs flex justify-between text-gray-500 mt-1 px-1">
                                    <span>Sedikit Rusak</span>
                                    <span>Sangat Rusak</span>
                                </div>
                            </div>
                            
                            <!-- Dampak Akademik -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="text-xs sm:text-sm font-medium text-gray-700">Dampak Akademik</label>
                                    <span id="dampak_value" class="text-xs font-semibold px-2 py-0.5 bg-red-100 text-red-700 rounded-full">3/5</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-book text-gray-300"></i>
                                    <input type="range" min="1" max="5" value="3" id="dampak_akademik_sarpras" name="dampak_akademik_sarpras" 
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-500"
                                        oninput="document.getElementById('dampak_value').textContent = this.value + '/5';
                                                document.getElementById('dampak_value_mobile').textContent = this.value + '/5';
                                                document.getElementById('dampak_akademik_mobile').value = this.value;">
                                    <i class="fas fa-book-open text-red-500"></i>
                                </div>
                                <div class="text-xs flex justify-between text-gray-500 mt-1 px-1">
                                    <span>Sedikit Berdampak</span>
                                    <span>Sangat Berdampak</span>
                                </div>
                            </div>
                            
                            <!-- Jumlah yang Membutuhkan -->
                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="text-xs sm:text-sm font-medium text-gray-700">Jumlah yang Membutuhkan</label>
                                    <span id="jumlah_value" class="text-xs font-semibold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">3/5</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user text-gray-300"></i>
                                    <input type="range" min="1" max="5" value="3" id="kebutuhan_sarpras" name="kebutuhan_sarpras" 
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                        oninput="document.getElementById('jumlah_value').textContent = this.value + '/5';
                                                document.getElementById('jumlah_value_mobile').textContent = this.value + '/5';
                                                document.getElementById('kebutuhan_mobile').value = this.value;">
                                    <i class="fas fa-users text-blue-500"></i>
                                </div>
                                <div class="text-xs flex justify-between text-gray-500 mt-1 px-1">
                                    <span>Sedikit Orang</span>
                                    <span>Banyak Orang</span>
                                </div>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize range slider values
        document.getElementById('kerusakan_value').textContent = document.getElementById('tingkat_kerusakan_sarpras').value + '/5';
        document.getElementById('dampak_value').textContent = document.getElementById('dampak_akademik_sarpras').value + '/5';
        document.getElementById('jumlah_value').textContent = document.getElementById('kebutuhan_sarpras').value + '/5';
        
        // Initialize mobile view sliders if they exist
        if(document.getElementById('kerusakan_value_mobile')) {
            document.getElementById('kerusakan_value_mobile').textContent = document.getElementById('tingkat_kerusakan_sarpras').value + '/5';
            document.getElementById('dampak_value_mobile').textContent = document.getElementById('dampak_akademik_sarpras').value + '/5';
            document.getElementById('jumlah_value_mobile').textContent = document.getElementById('kebutuhan_sarpras').value + '/5';
        }
        
        // Make range sliders more responsive to touch
        const rangeInputs = document.querySelectorAll('input[type="range"]');
        rangeInputs.forEach(input => {
            // Increase the hit area
            input.addEventListener('touchstart', function(e) {
                const touchX = e.touches[0].clientX;
                const rect = this.getBoundingClientRect();
                const percentage = (touchX - rect.left) / rect.width;
                this.value = Math.round(percentage * 4) + 1; // 1-5 scale
                
                // Update all connected labels (desktop and mobile versions)
                const outputId = this.getAttribute('oninput').split("'")[0].split('"')[0].split('(')[1].split(')')[0];
                document.getElementById(outputId).textContent = this.value + '/5';
                
                // Sync other slider if it exists
                const isMobile = this.id.includes('mobile');
                const otherSliderId = isMobile 
                    ? this.id.replace('_mobile', '')
                    : this.id + '_mobile';
                    
                if(document.getElementById(otherSliderId)) {
                    document.getElementById(otherSliderId).value = this.value;
                }
                
                // Prevent scrolling while adjusting the slider
                e.preventDefault();
            }, { passive: false });
        });
        
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
        document.getElementById('sarprasEditLaporanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            this.submit();
        });
        
        // Ensure modal can be scrolled on mobile
        document.getElementById('sarprasEditLaporanModal').addEventListener('touchmove', function(e) {
            e.stopPropagation();
        }, { passive: true });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Status-based criteria validation
        const statusSelect = document.getElementById('edit_status');
        const criteriaContainers = document.querySelectorAll('.criteria-container');
        const criteriaInputs = document.querySelectorAll('.criteria-input');
        
        // Add criteria-container class to the parent divs containing criteria sliders
        document.querySelectorAll('#tingkat_kerusakan_sarpras, #dampak_akademik_sarpras, #kebutuhan_sarpras')
            .forEach(el => {
                el.classList.add('criteria-input');
                el.closest('div.mb-4, div:not(.mb-4):has(> div > .criteria-input)').classList.add('criteria-container');
            });

        if(document.getElementById('tingkat_kerusakan_sarpras_mobile')) {
            document.querySelectorAll('#tingkat_kerusakan_sarpras_mobile, #dampak_akademik_sarpras_mobile, #kebutuhan_sarpras_mobile')
                .forEach(el => {
                    el.classList.add('criteria-input');
                    el.closest('.grid.grid-cols-1.sm\\:grid-cols-3.gap-4 > div').classList.add('criteria-container');
                });
        }
        
        // Function to toggle criteria requirement based on status
        function toggleCriteriaRequirement() {
            const isRequired = statusSelect.value === 'diproses';
            
            // Update the criteria section visibility and required attribute
            criteriaContainers.forEach(container => {
                container.style.opacity = isRequired ? '1' : '0.5';
            });
            
            criteriaInputs.forEach(input => {
                if(isRequired) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            });
            
            // Update message
            const criteriaMessage = document.getElementById('criteria-message');
            if(criteriaMessage) {
                criteriaMessage.style.display = isRequired ? 'block' : 'none';
            }
        }
        
        // Initial check on page load
        toggleCriteriaRequirement();
        
        // Listen for status changes
        statusSelect.addEventListener('change', toggleCriteriaRequirement);
        
        // Form validation before submit
        document.getElementById('sarprasEditLaporanForm').addEventListener('submit', function(e) {
            if(statusSelect.value === 'diproses') {
                // Check if all criteria fields have values
                let isValid = true;
                criteriaInputs.forEach(input => {
                    if(!input.value || input.value < 1) {
                        isValid = false;
                    }
                });
                
                if(!isValid) {
                    e.preventDefault();
                    alert('Kriteria penilaian wajib diisi untuk status "Diproses"');
                    return false;
                }
            }
            
            // Continue with existing submit handler...
        });
    });
</script>
