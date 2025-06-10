@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container px-4 py-8 mx-auto max-w-7xl">
    <!-- Search header with improved styling -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="flex items-center space-x-3 mb-4 md:mb-0">
                <h2 class="text-2xl font-bold text-gray-800">Hasil Pencarian</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    {{ $totalResults }} hasil ditemukan
                </span>
            </div>
            <div class="text-sm text-gray-600 font-medium">
                Pencarian untuk "<span class="text-indigo-600">{{ $query }}</span>"
            </div>
        </div>
        
        <!-- Enhanced search form with better UX -->
        <form action="{{ route('global.search') }}" method="GET" class="mb-8">
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ $query }}" 
                           class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg md:rounded-r-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                           placeholder="Search again...">
                    @if($query)
                    <button type="button" onclick="this.previousElementSibling.value = ''; this.previousElementSibling.focus();" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    @endif
                </div>
                <div class="flex">
                    <!-- Filter dropdown for larger screens -->
                    <div class="hidden md:block">
                        <select name="filter" class="h-full py-3 pl-3 pr-7 border-gray-300 border-y bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Kategori</option>
                            <option value="laporan" {{ request('filter') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="fasilitas" {{ request('filter') == 'fasilitas' ? 'selected' : '' }}>Tipe Fasilitas</option>
                            <option value="fas_ruang" {{ request('filter') == 'fas_ruang' ? 'selected' : '' }}>Unit Fasilitas</option>
                            <option value="ruang" {{ request('filter') == 'ruang' ? 'selected' : '' }}>Ruangan</option>
                            <option value="gedung" {{ request('filter') == 'gedung' ? 'selected' : '' }}>Gedung</option>
                            <option value="tugas" {{ request('filter') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                        </select>
                    </div>
                    <button type="submit" class="flex-shrink-0 bg-indigo-600 text-white px-6 py-3 rounded-lg md:rounded-l-none hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                        Search
                    </button>
                </div>
            </div>
            
            <!-- Filter dropdown for mobile -->
            <div class="mt-3 md:hidden">
                <select name="filter" class="w-full py-2 pl-3 pr-10 border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    <option value="laporan" {{ request('filter') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                    <option value="fasilitas" {{ request('filter') == 'fasilitas' ? 'selected' : '' }}>Tipe Fasilitas</option>
                    <option value="fas_ruang" {{ request('filter') == 'fas_ruang' ? 'selected' : '' }}>Unit Fasilitas</option>
                    <option value="ruang" {{ request('filter') == 'ruang' ? 'selected' : '' }}>Ruangan</option>
                    <option value="gedung" {{ request('filter') == 'gedung' ? 'selected' : '' }}>Bangunan</option>
                    <option value="tugas" {{ request('filter') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                </select>
            </div>
        </form>
        
        <!-- Search suggestions/recent searches -->
        <div class="flex flex-wrap gap-2">
            <span class="text-sm text-gray-500 mr-2">Pencarian Populer</span>
            <a href="{{ route('global.search', ['q' => 'rusak']) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors">rusak</a>
            <a href="{{ route('global.search', ['q' => 'menunggu']) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors">menunggu</a>
            <a href="{{ route('global.search', ['q' => 'kelas']) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors">kelas</a>
        </div>
    </div>
    
    <!-- No results state with improved visual feedback -->
    @if($totalResults === 0)
        <div class="p-12 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="text-center">
                <div class="relative mx-auto w-24 h-24 mb-6">
                    <svg class="absolute inset-0 text-indigo-100 transform scale-150" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"/>
                    </svg>
                    <svg class="relative h-16 w-16 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada hasil yang ditemukan</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Kami tidak dapat menemukan hasil pencarian yang berkaitan dengan "<span class="font-medium text-gray-700">{{ $query }}</span>". Coba gunakan kata kunci lainnya.</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('global.search') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Hapus Pencarian
                    </a>
                    <a href="{{ url('/') }}" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Search Analytics Summary -->
        <div class="mb-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @if(count($results['laporan'] ?? []) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Laporan</div>
                        <span class="text-lg font-semibold text-indigo-600">{{ count($results['laporan']) }}</span>
                    </div>
                </div>
            @endif
            @if(count($results['fasilitas'] ?? []) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Fasilitas</div>
                        <span class="text-lg font-semibold text-indigo-600">{{ count($results['fasilitas']) }}</span>
                    </div>
                </div>
            @endif
            @if(count($results['fas_ruang'] ?? []) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Unit Fasilitas</div>
                        <span class="text-lg font-semibold text-indigo-600">{{ count($results['fas_ruang']) }}</span>
                    </div>
                </div>
            @endif
            @if(count($results['ruang'] ?? []) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Ruangan</div>
                        <span class="text-lg font-semibold text-indigo-600">{{ count($results['ruang']) }}</span>
                    </div>
                </div>
            @endif
            @if(count($results['gedung'] ?? []) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Gedung</div>
                        <span class="text-lg font-semibold text-indigo-600">{{ count($results['gedung']) }}</span>
                    </div>
                </div>
            @endif
            @if(count($results['tugas'] ?? []) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-500">Tugas</div>
                        <span class="text-lg font-semibold text-indigo-600">{{ count($results['tugas']) }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Results with improved cards and enhanced visuals -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Laporan (Reports) -->
            @if(count($results['laporan'] ?? []) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan Kerusakan
                    </h3>
                    <span class="text-xs font-medium text-indigo-100 bg-indigo-700 bg-opacity-30 rounded-full px-3 py-1">
                        {{ count($results['laporan']) }} {{ Str::plural('item', count($results['laporan'])) }}
                    </span>
                </div>
                <div>
                    @foreach($results['laporan'] as $laporan)
                    <a href="{{ route('laporan.show', $laporan->id_laporan) }}" 
                       class="block hover:bg-indigo-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                        <div class="px-6 py-4 group">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 group-hover:text-indigo-700 transition-colors line-clamp-1">
                                    {{ $laporan->deskripsi }}
                                </p>
                                <div class="ml-3 flex-shrink-0 flex">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($laporan->status == 'menunggu')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($laporan->status == 'diproses')
                                            bg-blue-100 text-blue-800
                                        @elseif($laporan->status == 'selesai')
                                            bg-green-100 text-green-800
                                        @elseif($laporan->status == 'ditolak')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                                            @if($laporan->status == 'menunggu')
                                                bg-yellow-400
                                            @elseif($laporan->status == 'diproses')
                                                bg-blue-400
                                            @elseif($laporan->status == 'selesai')
                                                bg-green-400
                                            @elseif($laporan->status == 'ditolak')
                                                bg-red-400
                                            @endif
                                        "></span>
                                        {{ ucfirst($laporan->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span class="truncate">{{ $laporan->kode_laporan }}</span>
                            </div>
                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @if(count($results['laporan']) >= 10)
                <div class="bg-gray-50 px-6 py-3 flex justify-center">
                    <a href="{{ route('laporan.index') }}?search={{ $query }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                        View all reports
                        <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Fasilitas (Facility Types) -->
            @if(count($results['fasilitas'] ?? []) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828l-8.486 8.486a2 2 0 01-2.828 0l-2.172-2.172a2 2 0 010-2.828L7.343 11.5z" />
                        </svg>
                        Tipe Fasilitas
                    </h3>
                    <span class="text-xs font-medium text-indigo-100 bg-indigo-700 bg-opacity-30 rounded-full px-3 py-1">
                        {{ count($results['fasilitas']) }} {{ Str::plural('item', count($results['fasilitas'])) }}
                    </span>
                </div>
                <div>
                    @foreach($results['fasilitas'] as $fasilitas)
                    <a href="{{ route('tipe_fasilitas.edit', $fasilitas->id_fasilitas) }}" 
                       class="block hover:bg-indigo-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                        <div class="px-6 py-4 group">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                    {{ $fasilitas->nama_fasilitas }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>Kode: {{ $fasilitas->kode }}</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-500 line-clamp-1">
                                {{ $fasilitas->deskripsi ?: 'No description' }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Unit Fasilitas (Facility Units) -->
            @if(count($results['fas_ruang'] ?? []) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Unit Fasilitas
                    </h3>
                    <span class="text-xs font-medium text-indigo-100 bg-indigo-700 bg-opacity-30 rounded-full px-3 py-1">
                        {{ count($results['fas_ruang']) }} {{ Str::plural('item', count($results['fas_ruang'])) }}
                    </span>
                </div>
                <div>
                    @foreach($results['fas_ruang'] as $fasRuang)
                    <a href="{{ route('fasilitas.show', $fasRuang->id_fas_ruang) }}" 
                       class="block hover:bg-indigo-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                        <div class="px-6 py-4 group">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                    {{ $fasRuang->fasilitas->nama_fasilitas }}
                                </p>
                                <div class="ml-3 flex-shrink-0 flex">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($fasRuang->kondisi == 'baik')
                                            bg-green-100 text-green-800
                                        @elseif($fasRuang->kondisi == 'rusak_ringan')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($fasRuang->kondisi == 'rusak_berat')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                                            @if($fasRuang->kondisi == 'baik')
                                                bg-green-400
                                            @elseif($fasRuang->kondisi == 'rusak_ringan')
                                                bg-yellow-400
                                            @elseif($fasRuang->kondisi == 'rusak_berat')
                                                bg-red-400
                                            @endif
                                        "></span>
                                        {{ str_replace('_', ' ', ucfirst($fasRuang->kondisi)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>{{ $fasRuang->kode_fasilitas }}</span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>{{ $fasRuang->ruang->nama_ruang ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Ruangan (Rooms) -->
            @if(count($results['ruang'] ?? []) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Ruangan
                    </h3>
                    <span class="text-xs font-medium text-indigo-100 bg-indigo-700 bg-opacity-30 rounded-full px-3 py-1">
                        {{ count($results['ruang']) }} {{ Str::plural('item', count($results['ruang'])) }}
                    </span>
                </div>
                <div>
                    @foreach($results['ruang'] as $ruang)
                    <a href="{{ route('ruang.edit', $ruang->id_ruang) }}" 
                       class="block hover:bg-indigo-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                        <div class="px-6 py-4 group">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                    {{ $ruang->nama_ruang }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>Kode: {{ $ruang->kode }}</span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>{{ $ruang->gedung->nama_gedung ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Gedung (Buildings) -->
            @if(count($results['gedung'] ?? []) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Gedung
                    </h3>
                    <span class="text-xs font-medium text-indigo-100 bg-indigo-700 bg-opacity-30 rounded-full px-3 py-1">
                        {{ count($results['gedung']) }} {{ Str::plural('item', count($results['gedung'])) }}
                    </span>
                </div>
                <div>
                    @foreach($results['gedung'] as $gedung)
                    <a href="{{ route('gedung.edit', $gedung->id_gedung) }}" 
                       class="block hover:bg-indigo-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                        <div class="px-6 py-4 group">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                    {{ $gedung->nama_gedung }}
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <span>Kode: {{ $gedung->kode }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tugas (Tasks) -->
            @if(isset($results['tugas']) && count($results['tugas']) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Tugas
                    </h3>
                    <span class="text-xs font-medium text-indigo-100 bg-indigo-700 bg-opacity-30 rounded-full px-3 py-1">
                        {{ count($results['tugas']) }} {{ Str::plural('item', count($results['tugas'])) }}
                    </span>
                </div>
                <div>
                    @foreach($results['tugas'] as $tugas)
                    <a href="{{ route('tugas.show', $tugas->id) }}" 
                       class="block hover:bg-indigo-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                        <div class="px-6 py-4 group">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                    {{ $tugas->judul }}
                                </p>
                                <div class="ml-3 flex-shrink-0 flex">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($tugas->status == 'menunggu')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($tugas->status == 'sedang_dikerjakan')
                                            bg-blue-100 text-blue-800
                                        @elseif($tugas->status == 'selesai')
                                            bg-green-100 text-green-800
                                        @elseif($tugas->status == 'gagal')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                                            @if($tugas->status == 'menunggu')
                                                bg-yellow-400
                                            @elseif($tugas->status == 'sedang_dikerjakan')
                                                bg-blue-400
                                            @elseif($tugas->status == 'selesai')
                                                bg-green-400
                                            @elseif($tugas->status == 'gagal')
                                                bg-red-400
                                            @endif
                                        "></span>
                                        {{ str_replace('_', ' ', ucfirst($tugas->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                                </svg>
                                <span class="capitalize">{{ ucfirst($tugas->prioritas) }} priority</span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @endif
    
    <!-- "Back to top" button, appears when scrolling down -->
    <button id="backToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="fixed bottom-6 right-6 bg-indigo-600 text-white rounded-full p-3 shadow-lg opacity-0 transition-opacity duration-300 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
</div>

<script>
    // Show/hide the "Back to top" button based on scroll position
    window.addEventListener('scroll', function() {
        const backToTopBtn = document.getElementById('backToTopBtn');
        if (window.scrollY > 300) {
            backToTopBtn.classList.remove('opacity-0');
            backToTopBtn.classList.add('opacity-100');
        } else {
            backToTopBtn.classList.remove('opacity-100');
            backToTopBtn.classList.add('opacity-0');
        }
    });
    
    // Add keyboard shortcut for search (Ctrl+K or Command+K)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('input[name="q"]').focus();
        }
    });
</script>
@endsection