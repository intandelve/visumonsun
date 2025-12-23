<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Dashboard - VisuMonsun ID</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- CSS Animasi Angin (leaflet-velocity) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-velocity@1.1.0/dist/leaflet-velocity.min.css" />

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Pastikan peta memiliki tinggi */
        #map {
            height: 80vh; /* 80% dari tinggi layar */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Header / Navigation Bar -->
    <header class="bg-white shadow-md p-4 flex justify-between items-center">
    <div class="flex items-center space-x-2">
        <h1 class="text-xl font-bold text-gray-800">VisuMonsun ID</h1>
    </div>
    
    <nav class="hidden md:flex items-center space-x-6">
        <a href="{{ route('home') }}" class="text-blue-600 font-semibold">Dashboard</a>
        <a href="{{ route('statistics') }}" class="text-gray-500 hover:text-blue-600">Data Statistics</a>
        <a href="{{ route('forecast') }}" class="text-gray-500 hover:text-blue-600">Forecast</a>
        <a href="{{ route('about') }}" class="text-gray-500 hover:text-blue-600">About</a>
        <a href="{{ route('contact') }}" class="text-gray-500 hover:text-blue-600">Contact</a>
    </nav>
    
    <div class="flex items-center space-x-4">
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    Admin Panel
                </a>
            @else
                <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
            @endif
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    Log Out
                </button>
            </form>
        @else
            <!-- Tidak akan muncul karena route sudah protected -->
            <a href="{{ route('login') }}">Log in</a>
        @endauth
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-grow p-4 lg:p-6">
        <div class="flex flex-col lg:flex-row gap-6">
            
            <!-- Peta Interaktif (Lebih besar) -->
            <div class="flex-1 bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Kontainer Peta -->
                <div id="map" class="w-full"></div>
            </div>
            
            <!-- Sidebar Kontrol (Lebih sempit) -->
            <div class="w-full lg:w-96 bg-white p-6 rounded-xl shadow-lg space-y-6">
                <!-- Map Layers -->
                <div>
                    <h2 class="text-lg font-bold mb-3">MAP LAYERS</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <button id="btn-wind" class="flex flex-col items-center p-3 border-2 border-blue-500 bg-blue-50 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                            <span class="mt-2 text-sm font-semibold">Wind</span>
                        </button>
                        <button id="btn-rainfall" class="flex flex-col items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            <span class="mt-2 text-sm font-semibold">Rainfall</span>
                        </button>
                        <button id="btn-temp" class="flex flex-col items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                             <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12.75 12.75A6.75 6.75 0 0 0 3 9.75v-3.379a.75.75 0 0 1 .44-1.223l.135-.067a.75.75 0 0 1 .66.026l1.32.66A.75.75 0 0 0 6 6.38l.007.003a.75.75 0 0 1 .318.625A6.75 6.75 0 0 0 12.75 12.75Zm-9 3.379a.75.75 0 0 1-.44-1.223l-.135-.067a.75.75 0 0 1-.66.026l-1.32.66a.75.75 0 0 0-.324.64v3.379c0 .414.336.75.75.75h3.379a.75.75 0 0 0 .64-.324l.66-1.32a.75.75 0 0 1 .026-.66l-.067-.135a.75.75 0 0 1-1.223-.44Z" /></svg>
                            <span class="mt-2 text-sm font-semibold">Temperature</span>
                        </button>
                        <button id="btn-pressure" class="flex flex-col items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                           <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h12A2.25 2.25 0 0 0 20.25 14.25V3m-16.5 0h16.5m-16.5 0H3.75m16.5 0h.008v.008H20.25V3M10.5 6h.008v.008H10.5V6m3.75 0h.008v.008H14.25V6m-3.75 3h.008v.008H10.5V9m3.75 0h.008v.008H14.25V9m-3.75 3h.008v.008H10.5v-3.001M15 6.75v3m0 3v3.001" /></svg>
                            <span class="mt-2 text-sm font-semibold">Pressure</span>
                        </button>
                    </div>
                </div>
                
                <!-- Time Period -->
                <div>
                    <label for="time-slider" class="block text-lg font-bold mb-3">TIME PERIOD</label>
                    <span id="slider-label" class="block text-blue-600 font-semibold mb-2">January</span>
                    <input id="time-slider" type="range" min="1" max="12" value="1" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                </div>
                
                <!-- Province Filter -->
                <div>
                    <label for="province-filter" class="block text-lg font-bold mb-3">PROVINCE FILTER</label>
                    <select id="province-filter" class="w-full p-3 border border-gray-300 rounded-lg">
                        <option>All Indonesia</option>
                        <option>West Java</option>
                        <option>Central Java</option>
                        <option>East Java</option>
                        <option>DKI Jakarta</option>
                    </select>
                </div>
                
                <!-- Short-term Forecast -->
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <h3 class="text-lg font-bold text-blue-800 mb-2">Short-Term Forecast</h3>
                    <p class="text-sm text-blue-700">
                        Based on the latest LSTM model run, the upcoming wet season onset for West Java is predicted to be **slightly delayed**, starting around the **second week of December 2025**. Rainfall intensity is expected to be **+10-20% above average (wetter)**, particularly in western and central parts of Indonesia, indicating a potential moderate La Niña influence.
                    </p>
                </div>
            </div>
            
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="w-full bg-white p-4 text-center text-gray-500 text-sm mt-8 shadow-inner border-t border-gray-200">
        © 2025 VisuMonsun ID. All data provided by Copernicus Climate Change Service.
    </footer>
    
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