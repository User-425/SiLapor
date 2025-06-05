@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Fasilitas</h1>
        <a href="{{ route('fasilitas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Informasi Fasilitas</h2>
                <div class="space-y-3">
                    <p><span class="font-semibold">Kode:</span> {{ $fasRuang->kode_fasilitas }}</p>
                    <p><span class="font-semibold">Nama:</span> {{ $fasRuang->fasilitas->nama_fasilitas }}</p>
                    <p><span class="font-semibold">Ruang:</span> {{ $fasRuang->ruang->nama_ruang }}</p>
                    <p><span class="font-semibold">Gedung:</span> {{ $fasRuang->ruang->gedung->nama_gedung }}</p>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-semibold mb-4">QR Code</h2>
                <div class="flex justify-center">
                    {!! QrCode::size(200)->generate(route('laporan.quick', ['code' => base64_encode($fasRuang->id_fas_ruang)])) !!}
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('fasilitas.qr', $fasRuang->id_fas_ruang) }}" class="text-indigo-600 hover:text-indigo-800">
                        Cetak QR Code
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Statistik Laporan</h2>
            <a href="{{ route('fasilitas.history', $fasRuang->id_fas_ruang) }}" class="text-indigo-600 hover:text-indigo-800">
                Lihat riwayat lengkap laporan
            </a>
        </div>
    </div>
</div>
@endsection