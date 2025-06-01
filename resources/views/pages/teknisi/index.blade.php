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
                                      class="space-y-3 update-tugas-form">
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

@push('scripts')
<script>
document.querySelectorAll('.update-tugas-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success toast
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-500';
                toast.textContent = data.message;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            alert(error.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update';
        });
    });
});
</script>
@endpush
@endsection