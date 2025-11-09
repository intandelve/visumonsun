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
    <header class="bg-white shadow-md w-full p-4 flex justify-between items-center z-20">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            <h1 class="text-xl font-bold text-gray-800">VisuMonsun ID</h1>
        </div>
        
        <!-- Navigasi (About Aktif) -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="/" class="text-gray-500 hover:text-blue-600 transition duration-300">Dashboard</a>
            <a href="/statistics" class="text-gray-500 hover:text-blue-600 transition duration-300">Data Statistics</a>
            <a href="/forecast" class="text-gray-500 hover:text-blue-600 transition duration-300">Forecast</a>
            <a href="/about" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">About</a>
            <a href="/contact" class="text-gray-500 hover:text-blue-600 transition duration-300">Contact</a>
        </nav>
        
        <!-- Tombol EN -->
        <div class="flex items-center space-x-4">
             <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition duration-300">EN</button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-4 lg:p-6 space-y-6">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
            
            <!-- Page Title -->
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-8">About VisuMonsun ID</h1>
            
            <!-- Our Mission -->
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-blue-600 mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed text-lg">
                    VisuMonsun ID is a specialized web platform dedicated to the visualization and forecasting of wind and monsoon patterns in Indonesia. Our mission is to transform complex atmospheric data into clear, actionable insights for researchers, industry sectors (like agriculture, maritime, and renewable energy), and the public.
                </p>
                <p class="text-gray-600 leading-relaxed text-lg mt-4">
                    By providing detailed historical analysis and AI-driven forecasts, we aim to enhance preparedness and decision-making related to seasonal weather phenomena.
                </p>
            </div>

            <!-- Data Source & Technology -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <!-- Data Source -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Data Source: ERA5</h3>
                    <p class="text-gray-600">
                        Our platform is powered by **ERA5**, the fifth generation atmospheric reanalysis dataset from the Copernicus Climate Change Service (C3S). We use this high-resolution data to analyze decades of wind, rainfall, and pressure patterns.
                    </p>
                </div>
                <!-- Technology -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Technology</h3>
                    <p class="text-gray-600">
                        Our forecast models are built using **Python** with **LSTM (Deep Learning)** and **Prophet**. The web platform is driven by a **Laravel (PHP)** backend and a modern **JavaScript** (Leaflet.js, Chart.js) frontend.
                    </p>
                </div>
            </div>

            <!-- Team (Placeholder) -->
            <div>
                <h2 class="text-2xl font-bold text-blue-600 mb-4">Our Team</h2>
                <p class="text-gray-600 leading-relaxed text-lg">
                    We are a dedicated team of meteorologists, data scientists, and software engineers passionate about making climate data accessible and understandable.
                </p>
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