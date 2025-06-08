<header class="bg-white shadow-sm">
    <div class="flex items-center justify-between px-4 py-3 sm:px-6">
        <!-- Mobile menu button (only visible on small screens) -->
        <button class="text-gray-500 hover:text-gray-600 focus:outline-none mr-2 lg:hidden" onclick="toggleSidebar()" aria-label="Open sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Title - Truncates on small screens -->
        <h1 class="text-xl font-semibold text-gray-800 truncate sm:text-2xl max-w-[180px] sm:max-w-xs md:max-w-md">@yield('title', 'Dashboard')</h1>

        <div class="flex items-center">
            <!-- Search - Hidden on mobile, visible on md+ -->
            <div class="relative hidden md:block mx-4">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="text"
                    class="w-48 lg:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Search here...">
            </div>

            <!-- Search button - Only visible on mobile -->
            <button class="md:hidden p-2 mr-2 text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false, notifications: [], count: 0 }" 
                 @click.away="open = false"
                 x-init="
                    fetch('/notifications/unread')
                        .then(response => response.json())
                        .then(data => {
                            notifications = data.notifications;
                            count = data.count;
                        });
                 ">
                <button @click="open = !open" class="p-1 flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="count > 0" 
                              x-text="count > 9 ? '9+' : count" 
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        </span>
                    </div>
                </button>
                
                <!-- Dropdown menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100" 
                     x-transition:enter-start="transform opacity-0 scale-95" 
                     x-transition:enter-end="transform opacity-100 scale-100" 
                     x-transition:leave="transition ease-in duration-75" 
                     x-transition:leave-start="transform opacity-100 scale-100" 
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-md shadow-lg py-1 z-50"
                     style="max-width: calc(100vw - 1rem);">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                            <button @click="
                                fetch('/notifications/read-all', { 
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                    }
                                })
                                .then(() => {
                                    count = 0;
                                    notifications.forEach(n => n.read = true);
                                });
                            " 
                            class="text-xs text-indigo-600 hover:text-indigo-800">Mark all as read</button>
                        </div>
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                No new notifications
                            </div>
                        </template>
                        
                        <template x-for="notification in notifications" :key="notification.id">
                            <a @click.prevent="
                                fetch('/notifications/' + notification.id + '/read', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                    }
                                })
                                .then(() => {
                                    count = Math.max(0, count - 1);
                                    window.location.href = '/laporan/' + notification.data.id_laporan;
                                })
                             "
                             href="#" 
                             class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3 w-0 flex-1">
                                        <p class="font-medium text-gray-900" x-text="notification.data.title"></p>
                                        <p class="text-sm text-gray-500" x-text="notification.data.message"></p>
                                        <p class="text-xs text-gray-400 mt-1" x-text="notification.created_at"></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                    
                    <div class="px-4 py-2 border-t border-gray-100 text-center">
                        <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div x-data="{ isOpen: false }" class="relative ml-3">
                <button @click="isOpen = !isOpen" 
                        @keydown.escape="isOpen = false"
                        class="flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1" 
                        type="button">
                    <img class="h-8 w-8 rounded-full object-cover"
                        src="{{ Auth::user()->img_url ? asset('storage/'.Auth::user()->img_url) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama_lengkap).'&background=random' }}"
                        alt="{{ Auth::user()->nama_lengkap }}">
                    <div class="ml-2 hidden sm:block">
                        <div class="text-sm font-medium text-gray-700 truncate max-w-[100px] md:max-w-[150px]">{{ Auth::user()->nama_lengkap }}</div>
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
    
    <!-- Mobile search overlay (initially hidden) -->
    <div id="mobileSearchOverlay" class="hidden px-4 py-3 bg-gray-50 border-t border-b border-gray-200">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            <input type="text"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Search here...">
        </div>
    </div>
</header>

<script>
    // Toggle mobile search
    document.addEventListener('DOMContentLoaded', function() {
        const searchButton = document.querySelector('.md\\:hidden.p-2.mr-2');
        const searchOverlay = document.getElementById('mobileSearchOverlay');
        
        if (searchButton && searchOverlay) {
            searchButton.addEventListener('click', function() {
                searchOverlay.classList.toggle('hidden');
                if (!searchOverlay.classList.contains('hidden')) {
                    searchOverlay.querySelector('input').focus();
                }
            });
        }
    });
</script>