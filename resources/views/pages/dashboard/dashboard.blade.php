@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Total Revenue</h2>
                <p class="text-2xl font-semibold text-gray-800">$9,876</p>
                <p class="text-sm text-green-500">+8% from yesterday</p>
            </div>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Total Orders</h2>
                <p class="text-2xl font-semibold text-gray-800">123</p>
                <p class="text-sm text-green-500">+12% from last week</p>
            </div>
        </div>
    </div>
    
    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Total Customers</h2>
                <p class="text-2xl font-semibold text-gray-800">456</p>
                <p class="text-sm text-green-500">+5% from last month</p>
            </div>
        </div>
    </div>
    
    <!-- Card 4 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-500 text-sm">Growth Rate</h2>
                <p class="text-2xl font-semibold text-gray-800">+24%</p>
                <p class="text-sm text-green-500">+3% from last year</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="mt-8 bg-white rounded-lg shadow-sm">
    <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
        <a href="" class="text-indigo-600 hover:text-indigo-800">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">May 13, 2025</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">$240.00</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Processing
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">#ORD-004</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Emily+Davis&background=random" alt="Customer">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Emily Davis</div>
                                <div class="text-sm text-gray-500">emily@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">May 12, 2025</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">$95.25</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Cancelled
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Charts Section -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Sales Overview</h2>
        <div class="h-64">
            <!-- Chart will be rendered here -->
            <div class="flex h-full items-center justify-center">
                <div class="text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <p class="mt-2">Sales chart visualization</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Traffic Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Traffic Sources</h2>
        <div class="h-64">
            <!-- Chart will be rendered here -->
            <div class="flex h-full items-center justify-center">
                <div class="text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <p class="mt-2">Traffic sources visualization</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Charts can be initialized here using Chart.js or any other library
</script>
@endpush
