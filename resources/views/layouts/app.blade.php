<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLapor - @yield('title', 'Dashboard')</title>

    @vite('resources/css/app.css')
    @yield('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
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
                    <a href=""
                        class="flex items-center py-3 px-4 {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    <a href=""
                        class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('leaderboard') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <span class="ml-3">Leaderboard</span>
                    </a>

                    <a href=""
                        class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('orders') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        <span class="ml-3">Order</span>
                    </a>

                    <a href=""
                        class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('products') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Products</span>
                    </a>

                    <a href=""
                        class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('sales.report') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Sales Report</span>
                    </a>

                    <a href=""
                        class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('messages') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Messages</span>
                    </a>

                    <a href=""
                        class="flex items-center mt-4 py-3 px-4 {{ request()->routeIs('settings') ? 'bg-indigo-500 text-white rounded-lg' : 'text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Settings</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center py-3 px-4 text-gray-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3">Sign Out</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
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

                        <div class="flex items-center ml-4">
                            <img class="h-8 w-8 rounded-full object-cover"
                                src="https://ui-avatars.com/api/?name=Musfiq&background=random" alt="User Profile">
                            <div class="ml-2">
                                <div class="text-sm font-medium text-gray-700">Musfiq</div>
                                <div class="text-xs text-gray-500">Admin</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
