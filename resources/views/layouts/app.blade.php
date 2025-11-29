<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- CSS Leaflet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <!-- CSS Animasi Angin (leaflet-velocity) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-velocity@1.1.0/dist/leaflet-velocity.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                <!-- Check if we are on an admin page to show sidebar, or user dashboard -->
                @if (request()->is('admin*'))
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        
                        <div class="flex flex-col md:flex-row gap-6">
            
                            <div class="w-full md:w-1/4">
                                <div class="bg-white p-6 rounded-lg shadow-sm h-fit">
                                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Admin Menu</h3>
                                    <nav class="flex flex-col space-y-2">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg
                                            {{ request()->routeIs('admin.dashboard*') || request()->routeIs('rainfall*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4l.01.01M12 10l.01.01M12 14l.01.01M12 18l.01.01"></path></svg>
                                            <span>Rainfall Data</span>
                                        </a>
                                        <a href="{{ route('admin.wind_data.index') }}" class="flex items-center px-4 py-2 rounded-lg
                                            {{ request()->routeIs('admin.wind_data*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                            <span>Wind Data</span>
                                        </a>
                                        <a href="{{ route('admin.forecasts.index') }}" class="flex items-center px-4 py-2 rounded-lg
                                            {{ request()->routeIs('admin.forecasts*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.657 7.343A8 8 0 0117.657 18.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                                            <span>Forecasts</span>
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg
                                            {{ request()->routeIs('admin.users*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <span>Users</span>
                                        </a>
                                    </nav>
                                </div>
                            </div>
            
                            <div class="w-full md:w-3/4">
                                {{ $slot }}
                            </div>
            
                        </div>
            
                    </div>
                </div>
                @else
                    <!-- Not Admin Page, just render slot (User Dashboard) -->
                    {{ $slot }}
                @endif
            </main>
        </div>

        <!-- Memuat Library JavaScript -->

        <!-- jQuery (Wajib untuk leaflet-velocity) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Chart.js (Untuk Halaman Statistik) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Leaflet.js (Peta) -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <!-- Plugin Animasi Angin -->
        <script src="https://cdn.jsdelivr.net/npm/leaflet-velocity@1.1.0/dist/leaflet-velocity.min.js"></script>

        <!-- JavaScript Kustom Anda -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>