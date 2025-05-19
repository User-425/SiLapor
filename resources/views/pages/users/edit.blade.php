@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <!-- Page Heading -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Pengguna</h1>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm flex items-center shadow-sm hover:bg-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg text-gray-800">Form Edit Pengguna</h2>
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

            <form action="{{ route('users.update', $pengguna->id_pengguna) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pengguna->nama_lengkap) }}" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="email" name="email" value="{{ old('email', $pengguna->email) }}" required>
                </div>

                <div class="mb-4">
                    <label for="nama_pengguna" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="nama_pengguna" name="nama_pengguna" value="{{ old('nama_pengguna', $pengguna->nama_pengguna) }}" required>
                </div>

                <div class="mb-4">
                    <label for="nomor_telepon" class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $pengguna->nomor_telepon) }}">
                </div>

                <div class="mb-4">
                    <label for="kata_sandi" class="block text-gray-700 text-sm font-bold mb-2">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                           id="kata_sandi" name="kata_sandi">
                </div>

                <div class="mb-4">
                    <label for="kata_sandi_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                           id="kata_sandi_confirmation" name="kata_sandi_confirmation">
                </div>

                <div class="mb-6">
                    <label for="peran" class="block text-gray-700 text-sm font-bold mb-2">Peran</label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="peran" name="peran" required>
                        <option value="" disabled>Pilih peran</option>
                        <option value="admin" {{ old('peran', $pengguna->peran) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="mahasiswa" {{ old('peran', $pengguna->peran) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ old('peran', $pengguna->peran) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="tendik" {{ old('peran', $pengguna->peran) == 'tendik' ? 'selected' : '' }}>Tendik</option>
                        <option value="sarpras" {{ old('peran', $pengguna->peran) == 'sarpras' ? 'selected' : '' }}>Sarpras</option>
                        <option value="teknisi" {{ old('peran', $pengguna->peran) == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    </select>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
