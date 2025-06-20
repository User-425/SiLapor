<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLapor - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <!-- Tambahkan Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile sidebar backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 z-20 transition-opacity bg-gray-600 opacity-0 pointer-events-none lg:hidden"
            onclick="toggleSidebar()"></div>
        <!-- Sidebar -->
        <div id="sidebar-container" class="h-full lg:block" style="display: none;">
            @include('components.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            @include('components.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-100">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarContainer = document.getElementById('sidebar-container');
            const backdrop = document.getElementById('sidebar-backdrop');

            sidebar.classList.toggle('-translate-x-full');
            
            // Toggle sidebar container visibility
            if (window.innerWidth < 1024) { // Only for mobile
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebarContainer.style.display = 'none';
                    backdrop.classList.add('opacity-0', 'pointer-events-none');
                    backdrop.classList.remove('opacity-50');
                } else {
                    sidebarContainer.style.display = 'block';
                    backdrop.classList.remove('opacity-0', 'pointer-events-none');
                    backdrop.classList.add('opacity-50');
                }
            }
        }

        // Initialize sidebar visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarContainer = document.getElementById('sidebar-container');
            if (window.innerWidth >= 1024) { // lg breakpoint
                sidebarContainer.style.display = 'block';
            }
        });

        // Close sidebar on resize if we're on desktop
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            const sidebarContainer = document.getElementById('sidebar-container');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (window.innerWidth >= 1024) { // lg breakpoint
                sidebarContainer.style.display = 'block';
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-50');
            } else if (sidebar.classList.contains('-translate-x-full')) {
                sidebarContainer.style.display = 'none';
            }
        });

        // Listen for sidebar resize events to adjust layout if needed
        window.addEventListener('sidebarResized', function(e) {
            // You could add additional layout adjustments here if needed
            console.log('Sidebar resized to:', e.detail.width);
        });
    </script>

    @stack('scripts')
</body>

</html>