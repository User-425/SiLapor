@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Profil Saya</h2>
    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4 flex flex-col items-center">
            <img src="{{ $user->img_url ? asset('storage/'.$user->img_url) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_lengkap).'&background=random' }}"
                 class="h-24 w-24 rounded-full object-cover mb-2" alt="Profile Photo">
            <input type="file" name="img_url" class="block w-full text-sm text-gray-500">
            @error('img_url') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                   class="w-full border rounded px-3 py-2" required>
            @error('nama_lengkap') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border rounded px-3 py-2" required>
            @error('email') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Nama Pengguna</label>
            <input type="text" name="nama_pengguna" value="{{ old('nama_pengguna', $user->nama_pengguna) }}"
                   class="w-full border rounded px-3 py-2" required>
            @error('nama_pengguna') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                   class="w-full border rounded px-3 py-2">
            @error('nomor_telepon') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Kata Sandi Lama <span class="text-xs text-gray-500">(Wajib diisi jika ingin mengubah kata sandi)</span></label>
            <input type="password" name="kata_sandi_lama" class="w-full border rounded px-3 py-2">
            @error('kata_sandi_lama') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Kata Sandi Baru <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah)</span></label>
            <input type="password" name="kata_sandi" class="w-full border rounded px-3 py-2">
            @error('kata_sandi') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Konfirmasi Kata Sandi</label>
            <input type="password" name="kata_sandi_confirmation" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
    </form>
</div>
@endsection
