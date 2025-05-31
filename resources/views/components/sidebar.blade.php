<div class="w-64 bg-white border-r border-gray-200">
    <div class="p-4">
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
    </div>

    <nav class="mt-6">
        <div class="px-4">
            <a href="{{ route('dashboard') }}"
                class="flex items-center py-3 px-4 {{ request()->routeIs('dashboard*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>
            @auth
            @if(auth()->user()->peran === 'admin')
            <a href="{{ route('users.index') }}"
                class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('users.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                <span class="ml-3">Manajemen Pengguna</span>
            </a>

            <a href="/periode"
                class="flex items-center mt-4 py-3 px-4 {{ request()->is('periode*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Manajemen Periode</span>
            </a>

            <a href="/ruang"
                class="flex items-center mt-4 py-3 px-4 {{ request()->is('ruang*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2h1v2h1v-2h1v2h1v-2h1v2h1v-2h1v2h1v-2h1v2h1z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Manajemen Ruang</span>
            </a>

            <a href="/tipe_fasilitas"
                class="flex items-center mt-4 py-3 px-4 {{ request()->is('tipe_fasilitas*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Manajemen Tipe Fasilitas</span>
            </a>

            <a href="/fasilitas"
                class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('fasilitas.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Manajemen Unit Fasilitas</span>
            </a>

            <a href="/gedung"
                class="flex items-center mt-4 py-3 px-4 {{ request()->is('gedung*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Manajemen Gedung</span>
            </a>
            @endif

            @if(auth()->user()->peran === 'mahasiswa' || auth()->user()->peran === 'dosen' || auth()->user()->peran === 'tendik')
            <a href="/laporan" class="flex items-center mt-4 py-3 px-4 {{ request()->is('laporan*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Laporan</span>
            </a>
            @endif

            @if(auth()->user()->peran === 'sarpras')
            <a href="/laporan" class="flex items-center mt-4 py-3 px-4 {{ request()->is('laporan*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Verifikasi</span>
            </a>
            @endif

            @if(auth()->user()->peran === 'sarpras')
            <a href="{{ route('tugas.index') }}"
                class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('tugas.*') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.427z" clip-rule="evenodd" />
                </svg>
                <span class="ml-3">Penugasan Teknisi</span>
            </a>
            @endif
            @endauth
        </div>
    </nav>
</div>