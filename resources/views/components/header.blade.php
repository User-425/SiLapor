<header class="bg-white shadow-sm">
    <div class="flex items-center justify-between px-6 py-3">
        <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>

        <div class="flex items-center">
            <div class="relative mx-4">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="text"
                    class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Search here...">
            </div>

            <div class="relative">
                <button class="flex items-center focus:outline-none">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                    </div>
                </button>
            </div>

            <div x-data="{ isOpen: false }" class="relative ml-4">
                <button @click="isOpen = !isOpen" 
                        @keydown.escape="isOpen = false"
                        class="flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1" 
                        type="button">
                    <img class="h-8 w-8 rounded-full object-cover"
                        src="{{ Auth::user()->img_url ? asset('storage/'.Auth::user()->img_url) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama_lengkap).'&background=random' }}"
                        alt="{{ Auth::user()->nama_lengkap }}">
                    <div class="ml-2">
                        <div class="text-sm font-medium text-gray-700">{{ Auth::user()->nama_lengkap }}</div>
                        <div class="text-xs text-gray-500">{{ ucfirst(Auth::user()->peran) }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 ml-1 text-gray-400 transform transition-transform duration-200"
                         :class="{ 'rotate-180': isOpen }"
                         viewBox="0 0 20 20" 
                         fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="isOpen" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     @click.away="isOpen = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                    <a href="{{ route('profile') }}" 
                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" 
                             xmlns="http://www.w3.org/2000/svg" 
                             viewBox="0 0 20 20" 
                             fill="currentColor">
                            <path fill-rule="evenodd" 
                                  d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" 
                                  clip-rule="evenodd" />
                        </svg>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500" 
                                 xmlns="http://www.w3.org/2000/svg" 
                                 viewBox="0 0 20 20" 
                                 fill="currentColor">
                                <path fill-rule="evenodd" 
                                      d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" 
                                      clip-rule="evenodd" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>