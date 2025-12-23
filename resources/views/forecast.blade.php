<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast - VisuMonsun ID</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Leaflet -->
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
            <a href="{{ route('forecast') }}" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Forecast</a>
            <a href="{{ route('about') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">About</a>
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
    <main class="flex-grow p-4 lg:p-6 space-y-6 container mx-auto">
        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-800">Seasonal Forecast</h1>

        <!-- Forecast Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Ringkasan & Download -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Outlook -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <!-- ID BARU DI SINI -->
                    <h2 id="forecast-outlook-title" class="text-2xl font-bold mb-4">Loading forecast...</h2>
                    <!-- ID BARU DI SINI -->
                    <p id="forecast-outlook-content" class="text-gray-600 leading-relaxed">Please wait, fetching the latest seasonal outlook from our model...</p>
                </div>
                
                <!-- Onset Prediction -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
                    <!-- ID BARU DI SINI -->
                    <h3 id="forecast-onset-title" class="text-xl font-bold text-blue-800 mb-2">Loading onset data...</h3>
                    <!-- ID BARU DI SINI -->
                    <p id="forecast-onset-content" class="text-blue-700 font-medium">Please wait...</p>
                </div>
            </div>
            
            <!-- Kolom Kanan: Download -->
            <div class="bg-white p-6 rounded-xl shadow-lg h-fit">
                <h3 class="text-xl font-bold mb-4">Download Full Report</h3>
                <p class="text-gray-600 text-sm mb-4">Get the detailed PDF report for a specific region.</p>
                <label for="region-select" class="block text-sm font-semibold text-gray-700 mb-2">Select Region</label>
                <select id="region-select" class="w-full p-3 border border-gray-300 rounded-lg mb-4">
                    <option>West Java</option>
                    <option>Central Java</option>
                    <option>East Java</option>
                </select>
                <button class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition duration-300">
                    Download PDF
                </button>
            </div>
            
        </div>
        
        <!-- Peta Anomali (Masih Statis) -->
        <div class="bg-white p-6 rounded-xl shadow-lg mt-6">
            <h2 class="text-2xl font-bold mb-4">Forecast Anomaly Maps</h2>
            <p class="text-gray-600 mb-6">Comparing predicted conditions against the 30-year average.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-lg text-center mb-2">Rainfall Anomaly (Next 3 Months)</h3>
                    <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                        [Placeholder Peta Anomali Curah Hujan]
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-center mb-2">Wind Pattern Anomaly (850mb)</h3>
                     <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                        [Placeholder Peta Anomali Angin]
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