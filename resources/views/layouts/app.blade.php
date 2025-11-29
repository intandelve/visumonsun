<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VisuMonsun') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex min-h-screen">
            
            <!-- SIDEBAR (KIRI - Menu Admin) -->
            <!-- Lebar tetap 64 (256px), background putih, border kanan -->
            <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col shrink-0">
                
                <!-- Logo Area -->
                <div class="h-16 flex items-center px-6 border-b border-gray-200">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600 flex items-center">
                        <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                        VisuMonsun
                    </a>
                </div>

                <!-- Menu Navigasi -->
                <div class="flex-1 overflow-y-auto p-4">
                    <nav class="space-y-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Data Management</p>
                        
                        <!-- Link Dashboard (Rainfall) -->
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors
                            {{ request()->routeIs('dashboard*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Rainfall Data
                        </a>

                        <!-- Link Wind Data -->
                        <a href="{{ route('admin.wind_data.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors
                            {{ request()->routeIs('admin.wind_data*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                            Wind Data
                        </a>

                        <!-- Link Forecasts -->
                        <a href="{{ route('admin.forecasts.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors
                            {{ request()->routeIs('admin.forecasts*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Forecasts
                        </a>

                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">System</p>

                        <!-- Link Users -->
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors
                            {{ request()->routeIs('admin.users*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Users
                        </a>
                    </nav>
                </div>

                <!-- User Profile at Bottom -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div>
                            <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-2 py-1 text-sm text-red-600 hover:bg-red-50 rounded">
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- KONTEN UTAMA (KANAN) -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Mobile Header -->
                <header class="bg-white shadow md:hidden flex justify-between items-center p-4 z-10">
                    <span class="font-bold text-gray-800">Admin Panel</span>
                    <!-- Mobile menu button placeholder -->
                    <button class="text-gray-500">Menu</button>
                </header>

                <!-- Main Slot -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    
                    <!-- Header Page (Opsional) -->
                    @if (isset($header))
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                {{ $header }}
                            </h2>
                        </div>
                    @endif

                    <!-- Content -->
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>