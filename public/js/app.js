// Menunggu hingga seluruh halaman HTML selesai dimuat
document.addEventListener("DOMContentLoaded", function () {

    console.log("Website script loaded!");

    // --- LOGIKA UNTUK HALAMAN DASHBOARD (/) ---
    const mapContainer = document.getElementById('map');
    let velocityLayer; // Simpan layer di sini agar bisa diakses nanti

    // 2. Periksa apakah kita ada di halaman dashboard
    if (mapContainer) {
        console.log("Map container found, initializing map...");
        const map = L.map('map').setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        fetch('/api/wind-map-data')
            .then(response => response.json())
            .then(data => {
                console.log("LIVE wind data loaded successfully from API.");
                if (data && data.length > 0) {
                    velocityLayer = L.velocityLayer({
                        displayValues: true,
                        displayOptions: {
                            velocityType: 'Wind',
                            position: 'bottomleft',
                            emptyString: 'No wind data'
                        },
                        data: data,
                        maxVelocity: 15
                    });
                    velocityLayer.addTo(map);
                }
                console.log("Velocity layer added to map by default.");
            })
            .catch(error => console.error('Error loading wind map data from API:', error));

        // Fungsionalitas Tombol Layer
        const btnWind = document.getElementById('btn-wind');
        const btnRainfall = document.getElementById('btn-rainfall');
        if (btnWind && btnRainfall) {
            btnWind.addEventListener('click', () => {
                if (velocityLayer && !map.hasLayer(velocityLayer)) map.addLayer(velocityLayer);
                btnWind.classList.add('border-blue-500', 'bg-blue-50');
                btnRainfall.classList.remove('border-blue-500', 'bg-blue-50');
            });
            btnRainfall.addEventListener('click', () => {
                if (velocityLayer && map.hasLayer(velocityLayer)) map.removeLayer(velocityLayer);
                btnRainfall.classList.add('border-blue-500', 'bg-blue-50');
                btnWind.classList.remove('border-blue-500', 'bg-blue-50');
            });
        }

        // Fungsionalitas Time Slider
        const timeSlider = document.getElementById('time-slider');
        const sliderLabel = document.getElementById('slider-label');
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        if (timeSlider) {
            timeSlider.addEventListener('input', () => {
                sliderLabel.textContent = months[timeSlider.value - 1];
            });
        }
    }


    // --- LOGIKA UNTUK HALAMAN DATA STATISTICS (/statistics) ---

    const chartOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: false } }
    };
    const comparisonChartOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: true } }
    };
    const rainfallChartCanvas = document.getElementById('rainfallChart');

    if (rainfallChartCanvas) {
        console.log("Statistics charts initializing...");

        // GRAFIK #1: Average Rainfall (LIVE DARI API #1)
        fetch('/api/rainfall-stats')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.month_name);
                const rainfallValues = data.map(item => item.rainfall_mm);
                new Chart(rainfallChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Rainfall (mm)',
                            data: rainfallValues,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                        }]
                    },
                    options: chartOptions
                });
                console.log("Rainfall chart initialized with LIVE API data.");
            })
            .catch(error => console.error("Gagal mengambil data API Rainfall:", error));

        // GRAFIK #2: Wind Speed Trend (LIVE DARI API #2)
        const windChartCanvas = document.getElementById('windTrendChart');
        if (windChartCanvas) {
            fetch('/api/wind-speed-stats')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.month_name);
                    const windValues = data.map(item => item.speed_ms);
                    new Chart(windChartCanvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Average Wind Speed (m/s)',
                                data: windValues,
                                borderColor: 'rgba(239, 68, 68, 1)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                fill: true,
                                tension: 0.1
                            }]
                        },
                        options: chartOptions
                    });
                    console.log("Wind speed chart initialized with LIVE API data.");
                })
                .catch(error => console.error("Gagal mengambil data API Wind Speed:", error));
        }

        // GRAFIK #3: Data Comparison (LIVE DARI API #3)
        const comparisonChartCanvas = document.getElementById('comparisonChart');
        if (comparisonChartCanvas) {
            fetch('/api/comparison-data')
                .then(response => response.json())
                .then(data => {
                    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const data1998 = data.filter(item => item.year === 1998).map(item => item.rainfall_mm);
                    const data2023 = data.filter(item => item.year === 2023).map(item => item.rainfall_mm);
                    new Chart(comparisonChartCanvas, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: '2023 Rainfall (mm)',
                                    data: data2023, 
                                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                    borderRadius: 6,
                                },
                                {
                                    label: '1998 Rainfall (mm) - El Niño',
                                    data: data1998,
                                    backgroundColor: 'rgba(234, 179, 8, 0.5)',
                                    borderRadius: 6,
                                }
                            ]
                        },
                        options: comparisonChartOptions
                    });
                    console.log("Comparison chart initialized with LIVE API data.");
                })
                .catch(error => console.error("Gagal mengambil data API Comparison:", error));
        }
    }
    
    // ==========================================================
    // LOGIKA BARU UNTUK HALAMAN FORECAST (/forecast)
    // ==========================================================
    const forecastOutlookTitle = document.getElementById('forecast-outlook-title');
    
    // Periksa apakah kita ada di halaman forecast
    if (forecastOutlookTitle) {
        console.log("Forecast page initializing...");
        
        // Ambil data dari API forecast
        fetch('/api/seasonal-forecast')
            .then(response => response.json())
            .then(data => {
                
                // data sekarang adalah object: { seasonal_outlook: {...}, monsoon_onset: {...} }
                
                const outlook = data.seasonal_outlook;
                const onset = data.monsoon_onset;

                if (outlook) {
                    document.getElementById('forecast-outlook-title').textContent = outlook.title;
                    // Kita gunakan 'innerHTML' di sini agar tag <strong> bisa di-render
                    document.getElementById('forecast-outlook-content').innerHTML = outlook.content; 
                }
                
                if (onset) {
                    document.getElementById('forecast-onset-title').textContent = onset.title;
                    document.getElementById('forecast-onset-content').textContent = onset.content;
                }
                
                console.log("Forecast data loaded into page.");
            })
            .catch(error => console.error("Gagal mengambil data API Forecast:", error));
    }
    
});