@extends('layout')
@section('title', 'Notifications')
@section('nav-title', 'Notifications')
@section('content')
    <div class="w-full pt-10 min-h-[86vh] rounded-lg custom-shadow bg-white">
        <!-- Notifications Header -->
        <div class="px-6 pb-6">
            <h2 class="text-2xl font-semibold text-gray-800">All Notifications</h2>
            <p class="text-gray-500 mt-1">Stay updated with your latest activities</p>
        </div>

        <!-- Notifications List -->
        <div class="mb-6 px-6 space-y-4">


            @if (session('notifications'))
                @foreach (session('notifications') as $notification)
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-800 font-medium">{{ $notification->title ?? 'No title' }}</p>
                                <p class="text-sm text-gray-500">{{ $notification->description ?? 'No message' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at ?? 'Few Moments Ago' }}</p>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Mark as Read</button>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

        <!-- If No Notifications -->
        <div class="mt-6 px-6 hidden">
            <div class="flex flex-col items-center justify-center h-64">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <p class="text-gray-500 mt-4 text-lg">No new notifications</p>
                <p class="text-gray-400 text-sm">You're all caught up!</p>
            </div>
        </div>
    </div>
@endsection
