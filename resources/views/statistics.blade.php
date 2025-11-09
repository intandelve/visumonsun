<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Statistics - VisuMonsun ID</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Leaflet (jika diperlukan) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Header / Navigation Bar -->
    <header class="bg-white shadow-md w-full p-4 flex justify-between items-center z-20">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            <h1 class="text-xl font-bold text-gray-800">VisuMonsun ID</h1>
        </div>
        
        <!-- Navigasi (Statistics Aktif) -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="/" class="text-gray-500 hover:text-blue-600 transition duration-300">Dashboard</a>
            <a href="/statistics" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Data Statistics</a>
            <a href="/forecast" class="text-gray-500 hover:text-blue-600 transition duration-300">Forecast</a>
            <a href="/about" class="text-gray-500 hover:text-blue-600 transition duration-300">About</a>
            <a href="/contact" class="text-gray-500 hover:text-blue-600 transition duration-300">Contact</a>
        </nav>
        
        <!-- Tombol EN -->
        <div class="flex items-center space-x-4">
             <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition duration-300">EN</button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-4 lg:p-6 space-y-6 container mx-auto">
        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-800">Data Statistics</h1>

        <!-- Filter Controls -->
        <div class="bg-white p-4 rounded-xl shadow-lg flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label for="data-var" class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Variable</label>
                <select id="data-var" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option>Rainfall</option>
                    <option>Wind Speed</option>
                </select>
            </div>
            <div class="flex-1">
                <label for="region" class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Province</label>
                <select id="region" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option>All Indonesia</option>
                    <option>West Java</option>
                </select>
            </div>
            <div class="flex-1">
                <label for="date-range" class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Time Range</label>
                <select id="date-range" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option>Last 12 Months</option>
                    <option>Last 5 Years</option>
                </select>
            </div>
        </div>

        <!-- Chart Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Grafik #1: Average Rainfall -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold mb-4">Average Rainfall (2024)</h2>
                <!-- Wrapper untuk perbaiki bug "grafik raksasa" -->
                <div class="h-96 relative">
                    <canvas id="rainfallChart"></canvas>
                </div>
            </div>
            
            <!-- Grafik #2: Wind Speed Trend -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold mb-4">Wind Speed Trend</h2>
                <!-- Wrapper untuk perbaiki bug "grafik raksasa" -->
                <div class="h-96 relative">
                    <canvas id="windTrendChart"></canvas>
                </div>
            </div>

        </div> <!-- Akhir dari grid pertama -->

        <!-- Grafik #3: Data Comparison (BAGIAN YANG HILANG) -->
        <div class="bg-white p-6 rounded-xl shadow-lg mt-6">
            <h2 class="text-xl font-bold mb-4">Data Comparison (Rainfall 1998 vs 2023)</h2>
            <!-- Wrapper untuk perbaiki bug "grafik raksasa" -->
            <div class="h-96 relative">
                <canvas id="comparisonChart"></canvas> <!-- <-- INI KANVAS YANG HILANG -->
            </div>
        </div>

    </main>
    
    <!-- Footer -->
    <footer class="w-full bg-white p-4 text-center text-gray-500 text-sm mt-8 shadow-inner border-t border-gray-200">
        © 2025 VisuMonsun ID. All data provided by Copernicus Climate Change Service.
    </footer>
    
    <!-- Library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- JavaScript Kustom Anda -->
    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>