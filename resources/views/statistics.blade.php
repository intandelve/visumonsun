<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Statistics - VisuMonsun ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    
    <!-- HEADER -->
    <header class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
            </svg>
            <h1 class="text-xl font-bold text-gray-800">VisuMonsun ID</h1>
        </div>
        
        <nav class="hidden md:flex items-center space-x-6">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Dashboard</a>
            <a href="{{ route('statistics') }}" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Data Statistics</a>
            <a href="{{ route('forecast') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Forecast</a>
            <a href="{{ route('about') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">About</a>
            <a href="{{ route('contact') }}" class="text-gray-500 hover:text-blue-600 transition duration-300">Contact</a>
        </nav>
        
        <div class="flex items-center space-x-4">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
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
            @endauth
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Data Statistics</h1>
        
        <!-- FILTER SECTION -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Filter Data</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="start-date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="end-date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <select id="location" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Locations</option>
                        <option value="jakarta">Jakarta</option>
                        <option value="surabaya">Surabaya</option>
                        <option value="medan">Medan</option>
                    </select>
                </div>
            </div>
            <button onclick="applyFilter()" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Apply Filter
            </button>
        </div>

        <!-- STATISTICS CARDS -->
        <div class="grid grid-cols-1 md: grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Rainfall</p>
                        <p class="text-2xl font-bold text-gray-800">1,234 mm</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19. 5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Avg Wind Speed</p>
                        <p class="text-2xl font-bold text-gray-800">12.5 m/s</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Data Points</p>
                        <p class="text-2xl font-bold text-gray-800">5,678</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Locations</p>
                        <p class="text-2xl font-bold text-gray-800">234</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Rainfall Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4">Monthly Rainfall Trend</h3>
                <canvas id="rainfallChart"></canvas>
            </div>

            <!-- Wind Speed Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4">Wind Speed Distribution</h3>
                <canvas id="windChart"></canvas>
            </div>
        </div>

        <!-- DATA TABLE -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Recent Data</h3>
                
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                        Manage Data
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rainfall (mm)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wind Speed (m/s)</th>
                            
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Example data row -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2025-12-17</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jakarta</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">45. 2</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8.5</td>
                            
                            @if(Auth:: check() && Auth::user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            @endif
                        </tr>
                        <!-- More rows would be dynamically generated -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-gray-600 text-sm mt-8">
        <p>&copy; 2025 VisuMonsun ID. All rights reserved. </p>
    </footer>

    <script>
        // Rainfall Chart
        const rainfallCtx = document.getElementById('rainfallChart').getContext('2d');
        new Chart(rainfallCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label:  'Rainfall (mm)',
                    data: [120, 150, 180, 220, 200, 170, 140, 130, 160, 190, 210, 180],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor:  'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive:  true,
                maintainAspectRatio: true
            }
        });

        // Wind Speed Chart
        const windCtx = document.getElementById('windChart').getContext('2d');
        new Chart(windCtx, {
            type: 'bar',
            data: {
                labels: ['0-5', '5-10', '10-15', '15-20', '20+'],
                datasets: [{
                    label: 'Frequency',
                    data: [45, 78, 62, 34, 12],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ]
                }]
            },
            options: {
                responsive:  true,
                maintainAspectRatio: true
            }
        });

        function applyFilter() {
            // Filter logic here
            console.log('Filter applied');
        }
    </script>
</body>
</html>