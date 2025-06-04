@extends('layouts.app')
@section('title', 'Beri Umpan Balik')

@push('styles')
<style>
    .rating-button {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 3px solid #e5e7eb; /* slate-200 */
        background: white !important; /* Base background */
        color: #6b7280; /* gray-500 */
        display: flex !important;
        align-items: center;
        justify-content: center;
        min-width: 64px; /* Corresponds to w-16 */
        min-height: 64px; /* Corresponds to h-16 */
        visibility: visible !important;
        pointer-events: auto !important;
        z-index: 10;
    }
    .rating-button i {
        color: #6b7280; /* gray-500, initial star color */
    }
    .rating-button:hover {
        transform: scale(1.1);
        border-color: #f59e0b !important; /* amber-500 */
        color: #f59e0b !important; /* Text color on hover */
    }
    .rating-button:hover i {
        color: #f59e0b !important; /* Star color on hover */
    }
    .rating-button.active {
        background: #f59e0b !important; /* amber-500, NEEDS !important */
        color: white !important;
        border-color: #f59e0b !important; /* amber-500, NEEDS !important */
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    .rating-button.active i {
        color: white !important; /* Star color when active */
    }
    /* Hover on active button can be slightly different if needed, e.g., darker shade */
    .rating-button.active:hover {
        background: #d97706 !important; /* amber-600, NEEDS !important */
        border-color: #d97706 !important; /* amber-600, NEEDS !important */
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .form-transition {
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow-lg p-8 form-transition">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-star text-yellow-500 mr-3"></i>
                Beri Umpan Balik
            </h2>
            <p class="text-gray-600">
                Untuk Laporan #{{ $laporan->id_laporan }} - {{ $laporan->judul ?? 'Tanpa Judul' }}
            </p>
            <div class="mt-3 text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                Dilaporkan pada {{ $laporan->created_at->format('d M Y, H:i') }}
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-2"></i>
                    <h4 class="text-red-800 font-medium">Terdapat kesalahan dalam form:</h4>
                </div>
                <ul class="text-red-700 text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('umpan_balik.store') }}" method="POST" id="feedbackForm" novalidate>
            @csrf
            <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">
            <input type="hidden" name="id_pengguna" value="{{ Auth::id() }}">
            <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">

            <div class="mb-8">
                <fieldset>
                    <legend class="block text-sm font-semibold text-gray-700 mb-4">
                        Berikan Rating Anda <span class="text-red-500">*</span>
                    </legend>
                    <div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl border-2 border-slate-200 mb-8" id="ratingContainer">
                        <div class="flex flex-wrap justify-center gap-4 mb-6" id="ratingButtons">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="text-center">
                                    <button type="button" class="rating-button w-16 h-16 rounded-full text-2xl flex items-center justify-center mb-2" data-rating="{{ $i }}" aria-label="Rating {{ $i }} - {{ [
                                        1 => 'Sangat Tidak Puas',
                                        2 => 'Tidak Puas',
                                        3 => 'Cukup Puas',
                                        4 => 'Puas',
                                        5 => 'Sangat Puas'
                                    ][$i] }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <div class="text-xs text-gray-600 max-w-20">
                                        {{ [
                                            1 => 'Sangat Tidak Puas',
                                            2 => 'Tidak Puas',
                                            3 => 'Cukup Puas',
                                            4 => 'Puas',
                                            5 => 'Sangat Puas'
                                        ][$i] }}
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <div class="text-center">
                            <div class="text-base text-gray-700 font-semibold min-h-6" id="ratingText">Pilih tingkat kepuasan Anda</div>
                            <div class="text-sm text-gray-500 italic mt-2" id="ratingDescription"></div>
                        </div>
                    </div>
                </fieldset>
                @error('rating')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="komentar" class="block text-sm font-semibold text-gray-700 mb-2">
                    Komentar (Opsional)
                </label>
                <div class="relative">
                    <textarea
                        name="komentar"
                        id="komentar"
                        rows="5"
                        placeholder="Bagikan pengalaman Anda tentang penanganan laporan ini..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none"
                        maxlength="500"
                    >{{ old('komentar') }}</textarea>
                    <div class="absolute bottom-2 right-2 text-xs text-gray-400" id="charCount">
                        0/500 karakter
                    </div>
                </div>
                @error('komentar')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('laporan.riwayat') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submitBtn">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Umpan Balik
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Informasi Umpan Balik</p>
                <p>Umpan balik Anda sangat berharga untuk membantu kami meningkatkan kualitas layanan. Setelah dikirim, umpan balik tidak dapat diubah.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const ratingButtons = document.querySelectorAll('.rating-button');
        const ratingInput = document.getElementById('ratingInput');
        const ratingText = document.getElementById('ratingText');
        const ratingDescription = document.getElementById('ratingDescription');
        const textarea = document.getElementById('komentar');
        const charCount = document.getElementById('charCount');
        const submitBtn = document.getElementById('submitBtn');

        if (!ratingButtons.length) {
            console.error('Tidak ada elemen .rating-button yang ditemukan!');
            return;
        }
        console.log('Jumlah tombol rating ditemukan:', ratingButtons.length);

        const ratingLabels = {
            1: { text: 'Sangat Tidak Puas', desc: 'Layanan sangat mengecewakan dan perlu perbaikan besar' },
            2: { text: 'Tidak Puas', desc: 'Layanan di bawah harapan dan perlu perbaikan' },
            3: { text: 'Cukup Puas', desc: 'Layanan standar, masih bisa ditingkatkan' },
            4: { text: 'Puas', desc: 'Layanan baik dan memuaskan' },
            5: { text: 'Sangat Puas', desc: 'Layanan luar biasa dan sangat memuaskan' }
        };

        if (ratingInput && ratingInput.value) {
            const initialRating = parseInt(ratingInput.value);
            if (!isNaN(initialRating)) {
                updateButtonDisplay(initialRating);
                updateRatingText(initialRating);
            }
        }

        ratingButtons.forEach((button, index) => {
            if (!button.dataset.rating) {
                console.error('Tombol ke-', index + 1, 'tidak memiliki data-rating!');
                return;
            }

            button.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);

                if (isNaN(rating) || rating < 1 || rating > 5) {
                    console.error('Rating tidak valid:', rating);
                    return;
                }
                console.log('Tombol rating diklik, rating:', rating);

                if (ratingInput) ratingInput.value = rating;
                updateButtonDisplay(rating);
                updateRatingText(rating);
            });
        });

        function updateButtonDisplay(selectedRating) {
            ratingButtons.forEach((button) => {
                const buttonRating = parseInt(button.dataset.rating);
                if (buttonRating === selectedRating) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }

        function updateRatingText(selectedRating) {
            if (ratingText && ratingDescription) {
                if (selectedRating > 0 && ratingLabels[selectedRating]) {
                    ratingText.textContent = `Rating ${selectedRating}/5 - ${ratingLabels[selectedRating].text}`;
                    ratingDescription.textContent = ratingLabels[selectedRating].desc;
                } else {
                    ratingText.textContent = 'Pilih tingkat kepuasan Anda';
                    ratingDescription.textContent = '';
                }
            }
        }

        function updateCharCount() {
            if (charCount && textarea) {
                const count = textarea.value.length;
                charCount.textContent = `${count}/500 karakter`;
                charCount.classList.toggle('text-red-500', count > 450);
                charCount.classList.toggle('font-medium', count > 450);
            }
        }

        if (textarea) {
            textarea.addEventListener('input', updateCharCount);
            updateCharCount(); // Initial count

            textarea.addEventListener('input', function() { // Auto-resize
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            if (textarea.value) { // Initial resize if content exists
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight) + 'px';
            }
        }

        const feedbackForm = document.getElementById('feedbackForm');
        if (feedbackForm && submitBtn && ratingInput) {
            feedbackForm.addEventListener('submit', function(e) {
                const currentRating = ratingInput.value;

                if (!currentRating) {
                    e.preventDefault();
                    alert('Silakan pilih rating kepuasan Anda terlebih dahulu.');
                    const ratingContainerEl = document.getElementById('ratingContainer');
                    if (ratingContainerEl) {
                        ratingContainerEl.scrollIntoView({ behavior: 'smooth' });
                        ratingContainerEl.style.animation = 'shake 0.5s ease-in-out';
                        setTimeout(() => {
                            ratingContainerEl.style.animation = '';
                        }, 500);
                    }
                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            });
        }
    } catch (error) {
        console.error('Error di script feedbackForm:', error);
    }
});
</script>
@endpush
@endsection