@extends('layouts.app')

@section('title', 'Detail Laporan Kerusakan')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
    <div class="flex items-center mb-6">
        <div class="ml-4">
            <h2 class="text-2xl font-extrabold text-gray-800 mb-1">Detail Laporan Kerusakan</h2>
            <div class="text-sm text-gray-500">#{{ $laporan->id_laporan }}</div>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Pelapor:</span>
                <span class="text-gray-900">{{ $laporan->pengguna->nama_lengkap }}</span>
            </div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Email:</span>
                <span class="text-gray-900">{{ $laporan->pengguna->email }}</span>
            </div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Tanggal Laporan:</span>
                <span class="text-gray-900">{{ $laporan->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Status:</span>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                    @if($laporan->status == 'menunggu_verifikasi') bg-yellow-100 text-yellow-800
                    @elseif($laporan->status == 'diproses') bg-blue-100 text-blue-800
                    @elseif($laporan->status == 'diperbaiki') bg-indigo-100 text-indigo-800
                    @elseif($laporan->status == 'selesai') bg-green-100 text-green-800
                    @elseif($laporan->status == 'ditolak') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                </span>
            </div>
        </div>
        <div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Lokasi:</span>
                <span class="text-gray-900">
                    {{ $laporan->fasilitasRuang->ruang->gedung->nama_gedung ?? '-' }} -
                    {{ $laporan->fasilitasRuang->ruang->nama_ruang ?? '-' }}
                </span>
            </div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Fasilitas:</span>
                <span class="text-gray-900">{{ $laporan->fasilitasRuang->fasilitas->nama_fasilitas ?? '-' }}</span>
            </div>
            <div class="mb-2">
                <span class="font-semibold text-gray-700">Kode Fasilitas:</span>
                <span class="text-gray-900">{{ $laporan->fasilitasRuang->kode_fasilitas ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="font-semibold text-gray-700 mb-1">Deskripsi Kerusakan:</div>
        <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-gray-800 shadow-inner">
            {{ $laporan->deskripsi }}
        </div>
    </div>

    <div class="mb-6">
        <div class="font-semibold text-gray-700 mb-1">Foto Laporan:</div>
        @if($laporan->url_foto)
            <img src="{{ asset('storage/'.$laporan->url_foto) }}" alt="Foto Laporan"
                 class="mt-2 rounded-lg shadow max-h-72 border border-gray-200">
        @else
            <span class="text-gray-500">Tidak ada foto</span>
        @endif
    </div>

    <div class="flex justify-end">
        <a href="{{ route('laporan.index') }}"
           class="inline-flex items-center px-5 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-lg shadow hover:bg-indigo-100 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
