<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - VisuMonsun ID</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Leaflet (diperlukan untuk ikon footer) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Header / Navigation Bar -->
    <header class="bg-white shadow-md sticky top-0 z-50 p-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
            </svg>
            <h1 class="text-xl font-bold text-gray-800">VisuMonsun ID</h1>
        </div>
        
        <!-- Navigation -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Dashboard</a>
            <a href="{{ route('statistics') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Data Statistics</a>
            <a href="{{ route('forecast') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Forecast</a>
            <a href="{{ route('about') }}" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">About</a>
            <a href="{{ route('contact') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Contact</a>
        </nav>
        
        <!-- Auth Section -->
        <div class="flex items-center space-x-4">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Admin Panel</a>
                @else
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">Log Out</button>
                </form>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 text-white py-16 px-4">
            <div class="max-w-5xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-4">Understanding Monsoons in Indonesia</h1>
                <p class="text-xl text-blue-50 max-w-3xl mx-auto">Explore the science behind monsoon patterns and their impact on life in the archipelago</p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 -mt-8 pb-12 space-y-12">
            
            <!-- What is Monsoon -->
            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800">What is a Monsoon?</h2>
                </div>
                <p class="text-gray-600 leading-relaxed text-lg mb-4">
                    A monsoon is a <strong>seasonal wind system</strong> that reverses direction every six months, bringing dramatic changes in rainfall patterns. In Indonesia, there are two main monsoons:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="bg-blue-50 p-6 rounded-xl border-l-4 border-blue-500">
                        <h3 class="text-xl font-bold text-blue-800 mb-2">🌧️ Southwest Monsoon (Nov - Mar)</h3>
                        <p class="text-gray-700">Brings the <strong>wet season</strong> from the Indian Ocean, causing high rainfall across most of Indonesia.</p>
                    </div>
                    <div class="bg-orange-50 p-6 rounded-xl border-l-4 border-orange-500">
                        <h3 class="text-xl font-bold text-orange-800 mb-2">☀️ Northeast Monsoon (Apr - Oct)</h3>
                        <p class="text-gray-700">Brings the <strong>dry season</strong> from the Asian and Australian continents, with minimal rainfall.</p>
                    </div>
                </div>
            </div>

            <!-- Why Monsoons Matter -->
            <div class="bg-gradient-to-br from-indigo-600 to-blue-600 p-8 rounded-2xl shadow-xl text-white">
                <h2 class="text-3xl font-bold mb-6 flex items-center space-x-3">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                    </svg>
                    <span>Why Monsoons Matter?</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/10 backdrop-blur p-5 rounded-xl">
                        <div class="text-4xl mb-3">🌾</div>
                        <h3 class="font-bold text-lg mb-2">Agriculture</h3>
                        <p class="text-blue-100">80% of Indonesia's rice production depends on monsoon rainfall. Monsoon onset predictions help farmers determine optimal planting times.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur p-5 rounded-xl">
                        <div class="text-4xl mb-3">🌊</div>
                        <h3 class="font-bold text-lg mb-2">Maritime</h3>
                        <p class="text-blue-100">Monsoon winds affect ocean currents, waves, and navigation safety. Fishermen use these patterns to determine fishing locations.</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur p-5 rounded-xl">
                        <div class="text-4xl mb-3">⚡</div>
                        <h3 class="font-bold text-lg mb-2">Energy</h3>
                        <p class="text-blue-100">Monsoon wind speeds determine wind energy potential. This data is crucial for planning wind power generation facilities.</p>
                    </div>
                </div>
            </div>

            <!-- Climate Phenomena -->
            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Related Climate Phenomena</h2>
                <div class="space-y-6">
                    <div class="border-l-4 border-red-500 pl-6 py-3">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">🔥 El Niño (Extreme Drought)</h3>
                        <p class="text-gray-600">Pacific Ocean warming that causes <strong>delayed wet monsoons</strong> and prolonged droughts. Can trigger forest fires and severe water shortages.</p>
                    </div>
                    <div class="border-l-4 border-blue-500 pl-6 py-3">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">💧 La Niña (Extreme Rainfall)</h3>
                        <p class="text-gray-600">Pacific Ocean cooling that <strong>strengthens wet monsoons</strong>, causing floods and landslides across various regions of Indonesia.</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-6 py-3">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">🌀 Indian Ocean Dipole</h3>
                        <p class="text-gray-600">Temperature differences between the western and eastern Indian Ocean that influence monsoon intensity in western Indonesia.</p>
                    </div>
                </div>
            </div>

            <!-- Our Mission & Technology -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-xl">
                    <h2 class="text-2xl font-bold text-blue-600 mb-4">🎯 Our Mission</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        VisuMonsun ID exists to <strong>transform complex climate data</strong> into understandable and actionable insights.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        We provide real-time wind pattern visualizations, historical rainfall analysis, and AI-based monsoon predictions to support decision-making across various sectors.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl">
                    <h2 class="text-2xl font-bold text-blue-600 mb-4">🔬 Technology</h2>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <span class="text-2xl">📊</span>
                            <div>
                                <p class="font-semibold text-gray-800">Data: ERA5 Reanalysis</p>
                                <p class="text-sm text-gray-600">Copernicus Climate Service - resolusi 0.25° (±30km)</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-2xl">🤖</span>
                            <div>
                                <p class="font-semibold text-gray-800">Model: LSTM & Prophet</p>
                                <p class="text-sm text-gray-600">Deep Learning untuk prediksi pola musiman</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-2xl">🗺️</span>
                            <div>
                                <p class="font-semibold text-gray-800">Visualisasi: Leaflet Velocity</p>
                                <p class="text-sm text-gray-600">Real-time animated wind field</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    
    <!-- Footer -->
    <footer class="w-full bg-white p-4 text-center text-gray-500 text-sm mt-8 shadow-inner border-t border-gray-200">
        © 2025 VisuMonsun ID. All data provided by Copernicus Climate Change Service.
    </footer>
    
    <!-- Library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Library Leaflet -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-velocity@1.1.0/dist/leaflet-velocity.min.js"></script>
    
    <!-- JavaScript Kustom Anda -->
    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>