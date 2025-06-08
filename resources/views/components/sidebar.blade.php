<div class="flex flex-col h-full bg-white border-r border-gray-200">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded bg-indigo-500 flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <span class="ml-2 text-xl font-bold text-gray-800">SiLapor</span>
        </div>
        <!-- Close button for mobile -->
        <button class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 lg:hidden" onclick="toggleSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="mt-4 flex-1 overflow-y-auto">
        <div class="px-3">
            <!-- Common menus for all users -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center py-2 px-3 {{ request()->routeIs('dashboard*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
                <span class="ml-3 text-sm">Dashboard</span>
            </a>

            @auth
            <!-- Admin Menus -->
            @if(auth()->user()->peran === 'admin')
            <a href="{{ route('users.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('users.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.660.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                <span class="ml-3 text-sm">Manajemen Pengguna</span>
            </a>

            <a href="{{ route('periode.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('periode.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Manajemen Periode</span>
            </a>

            <a href="{{ route('ruang.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('ruang.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2h1v2h1v-2h1v2h1v-2h1v2h1v-2h1v2h1v-2h1v2h1v-2h1v2h1v-2h1v2h1z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Manajemen Ruang</span>
            </a>

            <a href="{{ route('tipe_fasilitas.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('tipe_fasilitas.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Manajemen Tipe Fasilitas</span>
            </a>

            <a href="{{ route('fasilitas.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('fasilitas.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Manajemen Unit Fasilitas</span>
            </a>

            <a href="{{ route('gedung.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('gedung.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Manajemen Gedung</span>
            </a>
            @endif

            <!-- User Menus (Mahasiswa, Dosen, Tendik) -->
            @if(in_array(auth()->user()->peran, ['mahasiswa', 'dosen', 'tendik']))
            <a href="{{ route('laporan.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('laporan.index') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Laporan Kerusakan</span>
            </a>

            <a href="{{ route('laporan.riwayat') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('laporan.riwayat') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Riwayat Laporan</span>
            </a>
            @endif

            <!-- Sarpras Menus -->
            @if(auth()->user()->peran === 'sarpras')
            <a href="{{ route('laporan.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('laporan.*') && !request()->routeIs('laporan.export') && !request()->routeIs('laporan.riwayat') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Verifikasi Laporan</span>
            </a>

            <a href="{{ route('laporan.riwayat') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('laporan.riwayat') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Riwayat Laporan</span>
            </a>

            <a href="{{ route('tugas.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('tugas.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.427z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Penugasan Teknisi</span>
            </a>

            <a href="{{ route('batches.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('batches.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                </svg>
                <span class="ml-3 text-sm">Batch Perbaikan</span>
            </a>

            <a href="{{ route('umpan_balik.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('umpan_balik.index') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="ml-3 text-sm">Daftar Umpan Balik</span>
            </a>

            <a href="{{ route('laporan.export') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('laporan.export') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Export Laporan</span>
            </a>
            @endif

            <!-- Teknisi Menus -->
            @if(auth()->user()->peran === 'teknisi')
            <a href="{{ route('teknisi.index') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('teknisi.index') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Daftar Tugas</span>
            </a>

            <a href="{{ route('teknisi.riwayat') }}"
                class="flex items-center mt-2 py-2 px-3 {{ request()->routeIs('teknisi.riwayat') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3 text-sm">Riwayat Perbaikan</span>
            </a>
            @endif
            @endauth
        </div>
    </nav>
</div>
