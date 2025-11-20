// ==========================================================
// app.js – Script utama untuk Dashboard dan Statistics Page
// ==========================================================

document.addEventListener("DOMContentLoaded", function () {
    console.log("Website script loaded!");

    // --- LOGIKA UNTUK HALAMAN DASHBOARD (/) ---
    const mapContainer = document.getElementById("map");
    let velocityLayer;

    if (mapContainer) {
        console.log("Map container found, initializing map...");

        // Inisialisasi Leaflet Map (Indonesia)
        const map = L.map("map").setView([-2.5489, 118.0149], 5);

        // Tambahkan Tile Layer (OpenStreetMap)
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution:
                '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        // ==========================================================
        // AMBIL DATA ANGIN DARI API LARAVEL
        // ==========================================================
        fetch("/api/wind-map-data")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Raw wind data from API:", data);

                // Ambil item pertama (karena API mengembalikan array)
                // Cek apakah data valid
                if (!data || data.length === 0) {
                    console.error("Wind data is empty");
                    return;
                }

                const wind = data[0];

                if (!wind || !wind.data) {
                    console.error("Wind data format invalid:", wind);
                    return;
                }

                // --- KONVERSI FORMAT KE FORMAT LEAFLET-VELOCITY ---
                // Ini bagian jenius dari kode Anda: Memecah objek {u,v} menjadi array terpisah
                const uData = wind.data.map((d) => d.u);
                const vData = wind.data.map((d) => d.v);

                const headerU = {
                    ...wind.header,
                    parameterCategory: 2,
                    parameterNumber: 2,
                    parameterUnit: "m.s-1",
                };
                const headerV = {
                    ...wind.header,
                    parameterCategory: 2,
                    parameterNumber: 3,
                    parameterUnit: "m.s-1",
                };

                const velocityData = [
                    { header: headerU, data: uData },
                    { header: headerV, data: vData },
                ];

                console.log("Converted velocity data:", velocityData);

                // --- BUAT VELOCITY LAYER ---
                velocityLayer = L.velocityLayer({
                    displayValues: true,
                    displayOptions: {
                        velocityType: "Wind",
                        position: "bottomleft",
                        emptyString: "No wind data",
                    },
                    data: velocityData, // Gunakan data hasil konversi
                    maxVelocity: 15,
                });

                velocityLayer.addTo(map);
                console.log("Velocity layer added to map successfully.");
            })
            .catch((error) => {
                console.error("Error loading wind map data from API:", error);
            });

        // ... (Logika Tombol Layer & Slider tetap sama) ...
        // ==========================================================
        // LOGIKA UNTUK TOMBOL LAYER
        // ==========================================================
        const btnWind = document.getElementById("btn-wind");
        const btnRainfall = document.getElementById("btn-rainfall");

        if (btnWind) {
            btnWind.addEventListener("click", () => {
                if (velocityLayer && !map.hasLayer(velocityLayer)) {
                    map.addLayer(velocityLayer);
                }
                btnWind.classList.add("border-blue-500", "bg-blue-50");
                btnRainfall?.classList.remove("border-blue-500", "bg-blue-50");
            });
        }

        if (btnRainfall) {
            btnRainfall.addEventListener("click", () => {
                if (velocityLayer && map.hasLayer(velocityLayer)) {
                    map.removeLayer(velocityLayer);
                }
                btnRainfall.classList.add("border-blue-500", "bg-blue-50");
                btnWind?.classList.remove("border-blue-500", "bg-blue-50");
            });
        }

        // ==========================================================
        // TIME SLIDER UNTUK BULAN
        // ==========================================================
        const timeSlider = document.getElementById("time-slider");
        const sliderLabel = document.getElementById("slider-label");
        const months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];

        if (timeSlider) {
            timeSlider.addEventListener("input", () => {
                const monthName = months[timeSlider.value - 1];
                sliderLabel.textContent = monthName;
            });
        }
    }

    // --- LOGIKA UNTUK HALAMAN STATISTICS (/statistics) ---
    const chartOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: false } },
    };

    const comparisonChartOptions = {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: true } },
    };

    const rainfallChartCanvas = document.getElementById("rainfallChart");

    if (rainfallChartCanvas) {
        console.log("Statistics charts initializing...");

        // GRAFIK 1: Average Rainfall
        fetch("/api/rainfall-stats")
            .then((response) => response.json())
            .then((data) => {
                const labels = data.map((item) => item.month_name);
                const rainfallValues = data.map((item) => item.rainfall_mm);
                new Chart(rainfallChartCanvas, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: "Average Rainfall (mm)",
                                data: rainfallValues,
                                backgroundColor: "rgba(59, 130, 246, 0.5)",
                                borderColor: "rgba(59, 130, 246, 1)",
                                borderWidth: 1,
                                borderRadius: 6,
                            },
                        ],
                    },
                    options: chartOptions,
                });
            })
            .catch((error) =>
                console.error("Gagal mengambil data API Rainfall:", error)
            );

        // GRAFIK 2: Wind Speed Trend
        const windChartCanvas = document.getElementById("windTrendChart");
        if (windChartCanvas) {
            fetch("/api/wind-speed-stats")
                .then((response) => response.json())
                .then((data) => {
                    const labels = data.map((item) => item.month_name);
                    const windValues = data.map((item) => item.speed_ms);
                    new Chart(windChartCanvas, {
                        type: "line",
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: "Average Wind Speed (m/s)",
                                    data: windValues,
                                    borderColor: "rgba(239, 68, 68, 1)",
                                    backgroundColor: "rgba(239, 68, 68, 0.1)",
                                    fill: true,
                                    tension: 0.1,
                                },
                            ],
                        },
                        options: chartOptions,
                    });
                })
                .catch((error) =>
                    console.error("Gagal mengambil data API Wind Speed:", error)
                );
        }

        // GRAFIK 3: Data Comparison
        const comparisonChartCanvas =
            document.getElementById("comparisonChart");
        if (comparisonChartCanvas) {
            fetch("/api/comparison-data")
                .then((response) => response.json())
                .then((data) => {
                    const labels = [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ];
                    const data1998 = data
                        .filter((item) => item.year === 1998)
                        .map((item) => item.rainfall_mm);
                    const data2023 = data
                        .filter((item) => item.year === 2023)
                        .map((item) => item.rainfall_mm);

                    new Chart(comparisonChartCanvas, {
                        type: "bar",
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: "2023 Rainfall (mm)",
                                    data: data2023,
                                    backgroundColor: "rgba(59, 130, 246, 0.5)",
                                    borderRadius: 6,
                                },
                                {
                                    label: "1998 Rainfall (mm) - El Niño",
                                    data: data1998,
                                    backgroundColor: "rgba(234, 179, 8, 0.5)",
                                    borderRadius: 6,
                                },
                            ],
                        },
                        options: comparisonChartOptions,
                    });
                })
                .catch((error) =>
                    console.error("Gagal mengambil data API Comparison:", error)
                );
        }
    }

    // --- LOGIKA UNTUK HALAMAN FORECAST ---
    const forecastTitle = document.getElementById("forecast-outlook-title");
    if (forecastTitle) {
        fetch("/api/seasonal-forecast")
            .then((response) => response.json())
            .then((data) => {
                const outlook = data.seasonal_outlook;
                const onset = data.monsoon_onset;
                if (outlook) {
                    document.getElementById(
                        "forecast-outlook-title"
                    ).textContent = outlook.title;
                    document.getElementById(
                        "forecast-outlook-content"
                    ).innerHTML = outlook.content;
                }
                if (onset) {
                    document.getElementById(
                        "forecast-onset-title"
                    ).textContent = onset.title;
                    document.getElementById(
                        "forecast-onset-content"
                    ).textContent = onset.content;
                }
            });
    }
});
