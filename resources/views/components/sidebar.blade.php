<div class="flex flex-col h-screen sidebar-gradient border-r border-gray-200 shadow-lg relative transform transition-transform duration-300 lg:translate-x-0 -translate-x-full lg:static fixed z-30" id="sidebar">
    <div id="sidebar-resizer" class="absolute right-0 top-0 bottom-0 w-1 cursor-ew-resize hover:bg-indigo-500 hover:w-1.5 transition-all z-50 hidden lg:block" aria-label="Resize sidebar" role="separator" tabindex="0"></div>

    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center hover:opacity-80 transition-opacity">
            <div class="relative">
                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md logo-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 icon-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div class="absolute -top-1 -right-1 h-3 w-3 bg-green-400 rounded-full border-2 border-white"></div>
            </div>
            <span class="ml-3 text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">SiLapor</span>
        </a>
        <!-- Close button for mobile -->
        <button class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors lg:hidden"
            onclick="toggleSidebar()"
            aria-label="Close sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-2 flex-1 overflow-y-auto px-3 space-y-1" role="navigation">
        <!-- Dashboard - Always visible -->
        <a href="{{ route('dashboard') }}"
            class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl transition-all group text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
            role="menuitem">
            <div class="flex-shrink-0 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
            </div>
            <span>Dashboard</span>
            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                <div class="h-2 w-2 bg-indigo-500 rounded-full"></div>
            </div>
        </a>

        @auth
        <!-- Admin Section -->
        @if(auth()->user()->peran === 'admin')
        <div class="pt-4">
            <div class="px-4 mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration</h3>
                <div class="mt-2 h-px nav-divider"></div>
            </div>

            <a href="{{ route('users.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('users.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.660.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <span>Manajemen Pengguna</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('periode.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('periode.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Manajemen Periode</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('ruang.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('ruang.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2h10z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Manajemen Ruang</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('tipe_fasilitas.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('tipe_fasilitas.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Manajemen Tipe Fasilitas</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('fasilitas.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('fasilitas.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Manajemen Unit Fasilitas</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('gedung.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('gedung.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Manajemen Gedung</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
        @endif

        <!-- User Section (Mahasiswa, Dosen, Tendik) -->
        @if(in_array(auth()->user()->peran, ['mahasiswa', 'dosen', 'tendik']))
        <div class="pt-4">
            <div class="px-4 mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</h3>
                <div class="mt-2 h-px nav-divider"></div>
            </div>

            <a href="{{ route('laporan.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('laporan.index') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Laporan Kerusakan</span>
                <div class="ml-auto">
                    <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded-full group-[.active]:bg-white/20 group-[.active]:text-white">{{ auth()->user()->laporan()->where('status', 'menunggu_verifikasi')->count() }}</span>
                </div>
            </a>

            <a href="{{ route('laporan.riwayat') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('laporan.riwayat') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Riwayat Laporan</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
        @endif

        <!-- Sarpras Section -->
        @if(auth()->user()->peran === 'sarpras')
        <div class="pt-4">
            <div class="px-4 mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sarpras</h3>
                <div class="mt-2 h-px nav-divider"></div>
            </div>

            <a href="{{ route('laporan.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('laporan.*') && !request()->routeIs('laporan.export') && !request()->routeIs('laporan.riwayat') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Verifikasi Laporan</span>
                <div class="ml-auto">
                    <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded-full group-[.active]:bg-white/20 group-[.active]:text-white">{{ \App\Models\LaporanKerusakan::where('status', 'menunggu_verifikasi')->count() }}</span>
                </div>
            </a>

            <a href="{{ route('batches.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('batches.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                    </svg>
                </div>
                <span>Batch Perbaikan</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>


            <a href="{{ route('tugas.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('tugas.*') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.427z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Penugasan Teknisi</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('laporan.riwayat') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('laporan.riwayat') ? 'active' : '' }}">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 100 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Riwayat Laporan</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('umpan_balik.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('umpan_balik.index') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <span>Daftar Umpan Balik</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ route('laporan.export') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('laporan.export') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Export Laporan</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
        @endif

        <!-- Teknisi Section -->
        @if(auth()->user()->peran === 'teknisi')
        <div class="pt-4">
            <div class="px-4 mb-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teknisi</h3>
                <div class="mt-2 h-px nav-divider"></div>
            </div>

            <a href="{{ route('teknisi.index') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('teknisi.index') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                </div>

                @php
                $prioritasTertinggi = auth()->user()->tugas()
                ->whereIn('status', ['ditugaskan'])
                ->orderByRaw("FIELD(prioritas, 'tinggi', 'sedang', 'rendah')")
                ->first()?->prioritas;
                @endphp

                <span>Daftar Tugas</span>
                <div class="ml-auto flex items-center relative h-5 w-5">
                    @if($prioritasTertinggi === 'tinggi')
                    <!-- Ping Tinggi -->
                    <span class="absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75 animate-ping"></span>
                    <span class="absolute inline-flex h-3 w-3 rounded-full bg-red-600"></span>
                    @elseif($prioritasTertinggi === 'sedang')
                    <!-- Titik kuning -->
                    <span class="absolute inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
                    @elseif($prioritasTertinggi === 'rendah')
                    <!-- Titik hijau -->
                    <span class="absolute inline-flex h-3 w-3 rounded-full bg-green-500"></span>
                    @endif
                </div>
            </a>

            <a href="{{ route('teknisi.riwayat') }}"
                class="nav-item flex items-center py-3 px-4 text-sm font-medium rounded-xl text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 transition-all group {{ request()->routeIs('teknisi.riwayat') ? 'active' : '' }}"
                role="menuitem">
                <div class="flex-shrink-0 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>Riwayat Perbaikan</span>
                <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>
        @endif
        @endauth
    </nav>

    <!-- Footer with status indicator -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center text-xs text-gray-500">
            <div class="h-2 w-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
            <span>System Online</span>
        </div>
    </div>

    <style>
        /* Custom animations and transitions */
        .nav-item {
            transition: all 0.2s ease-in-out;
        }

        .nav-item:hover {
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            color: white !important;
        }

        .nav-item.active span,
        .nav-item.active svg {
            color: white !important;
        }

        .logo-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .icon-bounce:hover {
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-2px);
            }
        }

        .nav-divider {
            background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
        }

        #sidebar-resizer {
            transition: background-color 0.2s, width 0.2s;
        }

        #sidebar {
            min-width: 150px;
            max-width: 400px;
            transition: width 0.1s ease-in-out;
            width: 280px; /* Default width */
        }
        
        /* Only enable resize on large screens */
        @media (min-width: 1024px) {
            #sidebar {
                resize: horizontal;
                overflow: auto;
            }
        }

        /* On mobile, take full available width minus a small margin */
        @media (max-width: 1023px) {
            #sidebar {
                width: calc(vw) !important;
                max-width: 320px;
            }
        }

        .resizing {
            user-select: none;
            cursor: ew-resize !important;
        }
    </style>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            sidebar.classList.toggle('-translate-x-full');

            if (sidebar.classList.contains('-translate-x-full')) {
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-50');
            } else {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-50');
            }
        }

        // Add click handlers for navigation items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Allow navigation to proceed naturally
                // Remove active class from all items and reset colors
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                    nav.style.background = '';
                    nav.style.boxShadow = '';
                    nav.classList.add('text-gray-600');
                    nav.classList.remove('text-white');

                    // Reset text and icon color
                    const spans = nav.querySelectorAll('span, svg');
                    spans.forEach(span => {
                        span.style.color = '';
                    });
                });

                // Add active class to clicked item
                this.classList.add('active');
                this.classList.remove('text-gray-600');
                this.classList.add('text-white');

                // Apply active styles
                this.style.background = 'linear-gradient(135deg, #4f46e5 0%, #6366f1 100%)';
                this.style.boxShadow = '0 4px 12px rgba(79, 70, 229, 0.3)';

                // Force white text color for active state
                const spans = this.querySelectorAll('span, svg');
                spans.forEach(span => {
                    span.style.color = 'white';
                });
            });
        });

        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                const focusedElement = document.activeElement;
                if (focusedElement.classList.contains('nav-item')) {
                    focusedElement.style.outline = '2px solid #4f46e5';
                }
            }
        });

        // High-performance sidebar resizing with animation frame
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const resizer = document.getElementById('sidebar-resizer');
            let isResizing = false;
            let initialX, initialWidth;

            // Only enable resizing on desktop
            function enableResizing() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    // Set initial width from localStorage or default to 16rem
                    const savedWidth = localStorage.getItem('sidebarWidth');
                    if (savedWidth) {
                        sidebar.style.width = savedWidth + 'px';
                    }
                    
                    // Add event listeners for resizing
                    resizer.addEventListener('mousedown', initResize);
                    resizer.addEventListener('touchstart', initResize, { passive: false });
                } else {
                    // Remove event listeners on mobile
                    resizer.removeEventListener('mousedown', initResize);
                    resizer.removeEventListener('touchstart', initResize);
                }
            }

            // Call initially and on resize
            enableResizing();
            window.addEventListener('resize', enableResizing);

            function initResize(e) {
                isResizing = true;
                initialX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
                initialWidth = parseInt(getComputedStyle(sidebar).width);

                // Add visual indicator when resizing begins
                resizer.classList.add('bg-indigo-500', 'w-1.5');
                document.body.classList.add('select-none');
                sidebar.classList.add('resizing');

                // Add temporary event listeners
                if (e.type === 'touchstart') {
                    document.addEventListener('touchmove', resize, {
                        passive: false
                    });
                    document.addEventListener('touchend', stopResize);
                } else {
                    document.addEventListener('mousemove', resize);
                    document.addEventListener('mouseup', stopResize);
                }

                e.preventDefault();
            }

            function resize(e) {
                if (!isResizing) return;

                // Use requestAnimationFrame for smooth performance
                requestAnimationFrame(() => {
                    const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
                    const deltaX = clientX - initialX;
                    const newWidth = Math.max(150, Math.min(400, initialWidth + deltaX));

                    sidebar.style.width = newWidth + 'px';
                });

                if (e.type === 'touchmove') e.preventDefault();
            }

            function stopResize() {
                if (isResizing) {
                    // Save final width to localStorage
                    localStorage.setItem('sidebarWidth', sidebar.style.width);

                    // Clean up
                    isResizing = false;
                    resizer.classList.remove('bg-indigo-500', 'w-1.5');
                    document.body.classList.remove('select-none');
                    sidebar.classList.remove('resizing');

                    // Remove temporary event listeners
                    document.removeEventListener('mousemove', resize);
                    document.removeEventListener('mouseup', stopResize);
                    document.removeEventListener('touchmove', resize);
                    document.removeEventListener('touchend', stopResize);

                    // Dispatch event for other components that might need to adjust
                    window.dispatchEvent(new CustomEvent('sidebarResized', {
                        detail: {
                            width: sidebar.style.width
                        }
                    }));
                }
            }
        });
    </script>
</div>