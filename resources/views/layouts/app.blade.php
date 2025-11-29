<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <div class="flex flex-1">
                <!-- Sidebar (Admin Only) -->
                @if (Auth::user() && (Auth::user()->role ?? 'user') === 'admin')
                <aside class="w-64 bg-white border-r border-gray-200 hidden md:block">
                    <div class="h-full px-3 py-4 overflow-y-auto">
                        <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Admin Menu</h3>
                        <nav class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                                {{ request()->routeIs('admin.dashboard*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.dashboard*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4l.01.01M12 10l.01.01M12 14l.01.01M12 18l.01.01"></path></svg>
                                Rainfall Data
                            </a>
                            <a href="{{ route('admin.wind_data.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                                {{ request()->routeIs('admin.wind_data*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.wind_data*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                Wind Data
                            </a>
                            <a href="{{ route('admin.forecasts.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                                {{ request()->routeIs('admin.forecasts*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.forecasts*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.657 7.343A8 8 0 0117.657 18.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                                Forecasts
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md group
                                {{ request()->routeIs('admin.users*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('admin.users*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Users
                            </a>
                        </nav>
                    </div>
                </aside>
                @endif

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                    @if (isset($header))
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                                {{ $header }}
                            </h2>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
