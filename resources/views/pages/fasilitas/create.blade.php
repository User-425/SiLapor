@extends('layouts.app')

@section('title', 'Tambah Fasilitas Ruang')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Fasilitas Ruang</h1>
        <a href="{{ route('fasilitas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm flex items-center shadow-sm hover:bg-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
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

            <form action="{{ route('fasilitas.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="id_ruang" class="block text-gray-700 text-sm font-bold mb-2">Ruang</label>
                    <select id="id_ruang" name="id_ruang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Pilih Ruang</option>
                        @foreach($ruangs as $ruang)
                            <option value="{{ $ruang->id_ruang }}" {{ old('id_ruang') == $ruang->id_ruang ? 'selected' : '' }}>
                                {{ $ruang->nama_ruang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="id_fasilitas" class="block text-gray-700 text-sm font-bold mb-2">Fasilitas</label>
                    <select id="id_fasilitas" name="id_fasilitas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Pilih Fasilitas</option>
                        @foreach($fasilitas as $fas)
                            <option value="{{ $fas->id_fasilitas }}" {{ old('id_fasilitas') == $fas->id_fasilitas ? 'selected' : '' }}>
                                {{ $fas->nama_fasilitas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="kode_fasilitas" class="block text-gray-700 text-sm font-bold mb-2">Kode Fasilitas</label>
                    <input type="text" id="kode_fasilitas" name="kode_fasilitas" value="{{ old('kode_fasilitas') }}" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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