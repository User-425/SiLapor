@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Semua Notifikasi</h2>
        
        @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
                Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} border rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if(isset($notification->data['title']) && str_contains($notification->data['title'], 'Tugas'))
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        @elseif(isset($notification->data['title']) && str_contains($notification->data['title'], 'Status'))
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @elseif(isset($notification->data['title']) && str_contains($notification->data['title'], 'Feedback'))
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between">
                            <h3 class="text-base font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi' }}</h3>
                            <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $notification->data['message'] ?? 'Tidak ada pesan' }}</p>
                        
                        <div class="mt-3 flex">
                            @if(isset($notification->data['id_laporan']))
                                <a href="{{ route('laporan.show', $notification->data['id_laporan']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    Lihat Laporan
                                </a>
                            @elseif(isset($notification->data['id_tugas']))
                                <a href="{{ route('teknisi.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    Lihat Tugas
                                </a>
                            @endif
                            
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="ml-4">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                        Tandai sudah dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p>Tidak ada notifikasi</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection