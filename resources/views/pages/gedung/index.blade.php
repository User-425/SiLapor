@extends('layouts.app')

@section('title', 'Daftar Gedung')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Gedung</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Pencarian --}}
    <form action="{{ route('gedung.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari gedung..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </div>
    </form>

    {{-- Tombol Tambah --}}
    <div class="mb-3">
        <a href="{{ route('gedung.create') }}" class="btn btn-success">+ Tambah Gedung</a>
    </div>

    {{-- Tabel Gedung --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Gedung</th>
                <th>Deskripsi Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gedungs as $index => $gedung)
                <tr>
                    <td>{{ $index + $gedungs->firstItem() }}</td>
                    <td>{{ $gedung->nama_gedung }}</td>
                    <td>{{ $gedung->deskripsi_lokasi ?? '-' }}</td>
                    <td>
                        <a href="{{ route('gedung.edit', $gedung->id_gedung) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('gedung.destroy', $gedung->id_gedung) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus gedung ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada data gedung.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $gedungs->links() }}
    </div>
</div>
@endsection
