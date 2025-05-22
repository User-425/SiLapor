@extends('layouts.app')

@section('title', 'Tambah Gedung')

@section('content')
    <!-- Page Heading -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Gedung</h1>
        <a href="{{ route('gedung.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm flex items-center shadow-sm hover:bg-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg text-gray-800">Form Tambah Gedung</h2>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gedung.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="nama_gedung" class="block text-gray-700 text-sm font-bold mb-2">Nama Gedung</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="nama_gedung" name="nama_gedung" value="{{ old('nama_gedung') }}" required>
                </div>

                <div class="mb-6">
                    <label for="deskripsi_lokasi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Lokasi</label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="deskripsi_lokasi" name="deskripsi_lokasi" rows="4">{{ old('deskripsi_lokasi') }}</textarea>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection