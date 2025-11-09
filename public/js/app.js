// Menunggu hingga seluruh halaman HTML selesai dimuat
document.addEventListener("DOMContentLoaded", function () {

    console.log("Website script loaded!");

    // --- LOGIKA UNTUK HALAMAN DASHBOARD (/) ---
    const mapContainer = document.getElementById('map');
    let velocityLayer; // Simpan layer di sini agar bisa diakses nanti

    // 2. Periksa apakah kita ada di halaman dashboard
    if (mapContainer) {
        console.log("Map container found, initializing map...");

        // 3. Inisialisasi Peta Leaflet
        const map = L.map('map').setView([-2.5489, 118.0149], 5); // Koordinat Indonesia

        // 4. Tambahkan 'Tile Layer' (background peta)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // ==========================================================
        // Ambil data angin dari API Laravel
        // ==========================================================
        fetch('/api/wind-map-data') 
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("LIVE wind data loaded successfully from API.");

                // PERBAIKAN: Tambahkan pengecekan untuk memastikan data tidak null
                if (data) {
                    // 6. Konfigurasi dan BUAT velocity layer
                    velocityLayer = L.velocityLayer({
                        displayValues: true,
                        displayOptions: {
                            velocityType: 'Wind',
                            position: 'bottomleft',
                            emptyString: 'No wind data'
                        },
                        data: data[0],
                        maxVelocity: 15 // Kecepatan angin maks untuk skala warna
                    });

                    // Tampilkan layer angin secara default
                    velocityLayer.addTo(map);
                    console.log("Velocity layer added to map by default.");
                } else {
                    // Jika data null, jangan buat layer dan beri pesan error
                    console.error("Wind data from API is null. The velocity layer cannot be created.");
                }
            })
            .catch(error => console.error('Error loading wind map data from API:', error));

        // 7. Fungsionalitas untuk tombol layer
        const btnWind = document.getElementById('btn-wind');
        const btnRainfall = document.getElementById('btn-rainfall');
        // (Anda bisa tambahkan ID untuk tombol lain)

        if (btnWind) {
            btnWind.addEventListener('click', () => {
                if (velocityLayer && !map.hasLayer(velocityLayer)) {
                    map.addLayer(velocityLayer);
                }
                // Atur style tombol
                btnWind.classList.add('border-blue-500', 'bg-blue-50');
                btnRainfall.classList.remove('border-blue-500', 'bg-blue-50');
            });
        }

        if (btnRainfall) {
            btnRainfall.addEventListener('click', () => {
                if (velocityLayer && map.hasLayer(velocityLayer)) {
                    map.removeLayer(velocityLayer);
                }
                // Atur style tombol
                btnRainfall.classList.add('border-blue-500', 'bg-blue-50');
                btnWind.classList.remove('border-blue-500', 'bg-blue-50');
            });
        }

        // 8. Fungsionalitas untuk Time Slider
        const timeSlider = document.getElementById('time-slider');
        const sliderLabel = document.getElementById('slider-label');
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        if (timeSlider) {
            timeSlider.addEventListener('input', () => {
                const monthName = months[timeSlider.value - 1];
                sliderLabel.textContent = monthName;
                console.log(`Time slider changed to: ${monthName}`);
                // Di sini Anda akan mem-fetch data baru untuk bulan tersebut
            });
        }
    }


    // --- LOGIKA UNTUK HALAMAN DATA STATISTICS (/statistics) ---

    // Opsi default untuk semua grafik agar rapi
    const chartOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: false } }
    };
    
    // Opsi untuk grafik perbandingan (dengan legenda)
    const comparisonChartOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: true } } // Tampilkan legenda di sini
    };


    // 1. Cari elemen canvas utama
    const rainfallChartCanvas = document.getElementById('rainfallChart');

    // 2. Periksa apakah elemen itu ada di halaman yang sedang dibuka
    if (rainfallChartCanvas) {

        console.log("Statistics charts initializing...");

        // ==========================================================
        // GRAFIK #1: Average Rainfall (LIVE DARI API #1)
        // ==========================================================
        fetch('/api/rainfall-stats')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.month_name);
                const rainfallValues = data.map(item => item.rainfall_mm);

                const rainfallData = {
                    label: 'Average Rainfall (mm)',
                    data: rainfallValues,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                };

                new Chart(rainfallChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [rainfallData]
                    },
                    options: chartOptions
                });
                console.log("Rainfall chart initialized with LIVE API data.");
            })
            .catch(error => console.error("Gagal mengambil data API Rainfall:", error));


        // ==========================================================
        // GRAFIK #2: Wind Speed Trend (LIVE DARI API #2)
        // ==========================================================
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

        // ==========================================================
        // GRAFIK #3: Data Comparison (LIVE DARI API #3)
        // ==========================================================
        const comparisonChartCanvas = document.getElementById('comparisonChart');
        if (comparisonChartCanvas) {
            
            fetch('/api/comparison-data')
                .then(response => response.json())
                .then(data => {
                    
                    // Kita perlu memisahkan data 1998 dan 2023
                    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    
                    const data1998 = data
                        .filter(item => item.year === 1998)
                        .map(item => item.rainfall_mm);
                        
                    const data2023 = data
                        .filter(item => item.year === 2023)
                        .map(item => item.rainfall_mm);

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
                        options: comparisonChartOptions // Menggunakan opsi dengan legenda
                    });
                    console.log("Comparison chart initialized with LIVE API data.");
                
                })
                .catch(error => console.error("Gagal mengambil data API Comparison:", error));
        }
    }
});