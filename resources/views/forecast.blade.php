<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast Page - VisuMonsun ID</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Leaflet (jika diperlukan untuk peta anomali) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .chart-placeholder {
            min-height: 384px; /* h-96 */
            position: relative;
        }
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
        
        <!-- Navigasi (Forecast Aktif) -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="/" class="text-gray-500 hover:text-blue-600 transition duration-300">Dashboard</a>
            <a href="/statistics" class="text-gray-500 hover:text-blue-600 transition duration-300">Data Statistics</a>
            <a href="/forecast" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Forecast</a>
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
        <h1 class="text-3xl font-bold text-gray-800">Seasonal Forecast</h1>

        <!-- Summary & Download Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Narrative Summary -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold mb-4">Seasonal Outlook (Next 3 Months)</h2>
                <div class="space-y-4 text-gray-700">
                    <p>Based on the latest LSTM model run, the upcoming wet season onset for West Java is predicted to be **slightly delayed**, starting around the **second week of December 2025**.</p>
                    <p>Rainfall intensity is expected to be **10-15% above average (wetter)**, particularly in western and central parts of Indonesia, indicating a potential moderate La Niña influence.</p>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <h3 class="font-semibold text-blue-800">Monsoon Onset Prediction</h3>
                        <p class="text-blue-700">Predicted Start for West Java: <strong class="text-lg">Dec 8 - 14, 2025</strong></p>
                    </div>
                </div>
            </div>
            
            <!-- Download Report Card -->
            <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col">
                <h2 class="text-xl font-bold mb-4">Download Full Report</h2>
                <p class="text-gray-600 mb-4 text-sm">Get the detailed PDF report for a specific region.</p>
                <div>
                    <label for="region-report" class="block text-sm font-semibold text-gray-600 mb-1">Select Region</label>
                    <select id="region-report" class="w-full p-2 border border-gray-300 rounded-lg mb-4">
                        <option>West Java</option>
                        <option>Central Java</option>
                        <option>Kalimantan</option>
                        <option>All Indonesia</option>
                    </select>
                </div>
                <button class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition duration-300 mt-auto">
                    Download PDF
                </button>
            </div>
        </div>

        <!-- Anomaly Maps Section -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-bold mb-4">Forecast Anomaly Maps</h2>
            <p class="text-gray-600 mb-4">Comparing predicted conditions against the 30-year average.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Rainfall Anomaly Map -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Rainfall Anomaly (Next 3 Months)</h3>
                    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                        [Placeholder Peta Anomali Curah Hujan]
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Map shows areas predicted to be wetter (blue) or drier (brown) than normal.</p>
                </div>
                
                <!-- Wind Anomaly Map -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Wind Pattern Anomaly (850mb)</h3>
                    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                         [Placeholder Peta Anomali Angin]
                    </div>
                     <p class="text-sm text-gray-500 mt-2">Map shows predicted wind flow deviations from the seasonal average.</p>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="w-full bg-white p-4 text-center text-gray-500 text-sm mt-8 shadow-inner border-t border-gray-200">
        © 2025 VisuMonsun ID. All data provided by Copernicus Climate Change Service.
    </footer>
    
    <!-- Library Chart.js & Leaflet -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- JavaScript Kustom Anda -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>