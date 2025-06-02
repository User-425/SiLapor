@extends('layouts.app')

@section('title', 'QR Code Fasilitas')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-gray-800">QR Code Fasilitas</h2>
                <p class="text-gray-600">{{ $fasRuang->fasilitas->nama_fasilitas }}</p>
                <p class="text-sm text-gray-500">
                    Lokasi: {{ $fasRuang->ruang->gedung->nama_gedung }} - {{ $fasRuang->ruang->nama_ruang }}
                </p>
                <p class="text-sm text-gray-500">
                    Kode Unit: {{ $fasRuang->kode_fasilitas }}
                </p>
            </div>

            <div class="flex flex-col items-center space-y-4">
                <div class="p-4 bg-white rounded-lg shadow-sm">
                    {!! $qrcode !!}
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">Scan QR code untuk melaporkan kerusakan</p>
                    <button onclick="downloadQR()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Download QR
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function downloadQR() {
    // Try to find the SVG inside the QR code container
    const qrContainer = document.querySelector('.p-4.bg-white.rounded-lg.shadow-sm');
    const svg = qrContainer.querySelector('svg');
    if (svg) {
        const svgData = new XMLSerializer().serializeToString(svg);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            const a = document.createElement('a');
            a.download = 'qr-{{ $fasRuang->kode_fasilitas }}.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
        };
        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
    } else {
        // If not SVG, try to find an <img> (PNG QR code)
        const img = qrContainer.querySelector('img');
        if (img) {
            const a = document.createElement('a');
            a.download = 'qr-{{ $fasRuang->kode_fasilitas }}.png';
            a.href = img.src;
            a.click();
        } else {
            alert('QR code tidak ditemukan untuk diunduh.');
        }
    }
}
</script>
@endsection
