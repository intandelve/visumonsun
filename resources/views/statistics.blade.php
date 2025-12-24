<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics - VisuMonsun ID</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header / Navigation Bar (sama dengan dashboard utama) -->
    <header class="bg-white shadow-md sticky top-0 z-50 p-4 flex justify-between items-center">
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
    <main class="min-h-screen p-4 lg:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Data Statistics</h1>
                <p class="mt-2 text-gray-600">Monthly rainfall and wind speed metrics</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Rainfall</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalRainfall, 1) }} mm</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.32 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Average Rainfall</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($avgRainfall, 1) }} mm</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Average Wind Speed</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($avgWindSpeed, 2) }} m/s</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.5 17H9.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5h5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5zm2.5-4H7c-.55 0-1-.45-1-1s.45-1 1-1h10c.55 0 1 .45 1 1s-.45 1-1 1z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Months Recorded</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $rainfallCount }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Monthly Rainfall Trend</h3>
                    <div class="h-[320px]">
                        <canvas id="rainfallChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Wind Speed Distribution</h3>
                    <div class="h-[320px]">
                        <canvas id="windSpeedChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Rainfall vs Wind Speed</h3>
                <div class="h-[220px]">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>

            <!-- Data Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Monthly Rainfall</h3>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Month</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Rainfall (mm)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rainfallData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $data->month_name }}</td>
                                <td class="px-6 py-3 text-sm text-right text-gray-900">{{ number_format($data->rainfall_mm, 1) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Monthly Wind Speed</h3>
                    </div>
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Month</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Speed (m/s)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($windData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $data->month_name }}</td>
                                <td class="px-6 py-3 text-sm text-right text-gray-900">{{ number_format($data->speed_ms, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full bg-white p-4 text-center text-gray-500 text-sm mt-8 shadow-inner border-t border-gray-200">
        © 2025 VisuMonsun ID. All data provided by Copernicus Climate Change Service.
    </footer>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const colors = {
            blue: 'rgb(59, 130, 246)',
            red: 'rgb(239, 68, 68)',
            green: 'rgb(34, 197, 94)',
        };

        const rainfallData = {!! json_encode($rainfallData->map(function($item) {
            return ['month' => $item->month_name, 'rainfall' => (float)$item->rainfall_mm];
        })) !!};

        const windData = {!! json_encode($windData->map(function($item) {
            return ['month' => $item->month_name, 'speed' => (float)$item->speed_ms];
        })) !!};

        new Chart(document.getElementById('rainfallChart'), {
            type: 'line',
            data: {
                labels: rainfallData.map(d => d.month),
                datasets: [{
                    label: 'Rainfall (mm)',
                    data: rainfallData.map(d => d.rainfall),
                    borderColor: colors.blue,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: colors.blue,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('windSpeedChart'), {
            type: 'bar',
            data: {
                labels: windData.map(d => d.month),
                datasets: [{
                    label: 'Wind Speed (m/s)',
                    data: windData.map(d => d.speed),
                    backgroundColor: colors.red,
                    borderColor: 'rgb(220, 38, 38)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('comparisonChart'), {
            type: 'bar',
            data: {
                labels: rainfallData.map(d => d.month),
                datasets: [
                    {
                        label: 'Rainfall (mm)',
                        data: rainfallData.map(d => d.rainfall),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: colors.blue,
                        borderWidth: 1,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Wind Speed (m/s)',
                        data: windData.map(d => d.speed),
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: colors.red,
                        borderWidth: 1,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Rainfall (mm)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Wind Speed (m/s)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    </script>
</body>
</html>
