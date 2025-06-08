@extends('layouts.app')

@section('title', 'Ekspor Laporan')

@section('content')
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
    <p>{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
    <p>{{ session('error') }}</p>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm p-8 mb-6">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-download text-blue-600 mr-3"></i>
            Ekspor Laporan Kerusakan
        </h1>
        <p class="text-gray-600">Pilih periode dan format untuk mengekspor semua laporan kerusakan</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Download Per Periode
                </h2>
                <p class="text-gray-600">Export laporan dari periode tertentu dengan tab terpisah per status</p>
            </div>

            <form id="eksporForm" action="{{ route('laporan.download-export') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Pemilihan Periode -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <label for="periode_id" class="block text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Pilih Periode
                    </label>
                    <select id="periode_id" name="periode_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}">
                                {{ $periode->nama_periode }}
                                ({{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pemilihan Format -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <label class="block text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-file text-blue-500 mr-2"></i>
                        Pilih Format Export
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="format-option">
                            <input type="radio" name="format" value="excel" id="format_excel" class="hidden" required>
                            <label for="format_excel" class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-excel text-green-600 text-2xl mr-4"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">Excel (.xlsx)</p>
                                    <p class="text-sm text-gray-600">Format spreadsheet dengan multiple tab</p>
                                </div>
                            </label>
                        </div>
                        <div class="format-option">
                            <input type="radio" name="format" value="pdf" id="format_pdf" class="hidden">
                            <label for="format_pdf" class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-pdf text-red-600 text-2xl mr-4"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">PDF (.pdf)</p>
                                    <p class="text-sm text-gray-600">Format dokumen untuk cetak</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('format')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Ekspor -->
                <div class="text-center">
                    <button type="submit" id="submitBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center mx-auto">
                        <i class="fas fa-download mr-3"></i>
                        Ekspor Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('eksporForm');
    const submitBtn = document.getElementById('submitBtn');

    // Handle format selection styling
    document.querySelectorAll('input[name="format"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset all labels
            document.querySelectorAll('.format-option label').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-300');
            });

            // Highlight selected
            const selectedLabel = this.nextElementSibling;
            selectedLabel.classList.remove('border-gray-300');
            selectedLabel.classList.add('border-blue-500', 'bg-blue-50');
        });
    });

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Mengekspor...';
        submitBtn.disabled = true;

        // Reset button after delay
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="fas fa-download mr-3"></i>Ekspor Laporan';
            submitBtn.disabled = false;
        }, 10000);
    });
});
</script>
@endpush
