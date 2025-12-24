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

        fetch("/api/wind-map-data")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Raw wind data from API:", data);

                // If API returned multiple payloads (array), prefer the largest grid (ERA5)
                if (!data || data.length === 0) {
                    console.error("Wind data is empty");
                    return;
                }

                // Pick the element with the largest grid (nx*ny) or longest data array
                let wind;
                if (Array.isArray(data)) {
                    wind = data.reduce((best, item) => {
                        const itemSize =
                            item &&
                            item.header &&
                            item.header.nx &&
                            item.header.ny
                                ? item.header.nx * item.header.ny
                                : item && item.data
                                ? item.data.length
                                : 0;
                        const bestSize =
                            best &&
                            best.header &&
                            best.header.nx &&
                            best.header.ny
                                ? best.header.nx * best.header.ny
                                : best && best.data
                                ? best.data.length
                                : 0;
                        return itemSize > bestSize ? item : best;
                    }, data[0]);
                } else {
                    wind = data;
                }

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
                // Helpful debug: show selected header and data length so we can tune scale
                try {
                    console.log("Selected wind header:", wind.header);
                    console.log("Wind data length:", wind.data.length);
                } catch (e) {
                    /* ignore */
                }

                // --- BUAT VELOCITY LAYER ---
                // Use a more conservative default scale; users complained vectors were too large.
                // You can tune `velocityScale` to taste. Typical values: 0.02 - 0.5 depending on zoom.
                const defaultVelocityScale = 0.5;

                velocityLayer = L.velocityLayer({
                    displayValues: true,
                    displayOptions: {
                        velocityType: "Wind",
                        position: "bottomleft",
                        emptyString: "No wind data",
                    },
                    data: velocityData, // Gunakan data hasil konversi
                    // Max velocity shown in the legend (visual cap)
                    maxVelocity: 20,
                    // Smaller default scale to avoid oversized vectors
                    velocityScale: defaultVelocityScale,
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

        // Keep references to custom map layers so we can toggle them
        const mapLayers = {
            wind: null,
            rainfall: null,
            temperature: null,
            pressure: null,
        };

        if (btnWind) {
            btnWind.addEventListener("click", () => {
                // show wind layer
                if (velocityLayer && !map.hasLayer(velocityLayer)) {
                    map.addLayer(velocityLayer);
                }
                // hide other layers
                ["rainfall", "temperature", "pressure"].forEach((k) => {
                    if (mapLayers[k] && map.hasLayer(mapLayers[k]))
                        map.removeLayer(mapLayers[k]);
                });
                btnWind.classList.add("border-blue-500", "bg-blue-50");
                btnRainfall?.classList.remove("border-blue-500", "bg-blue-50");
            });
        }

        if (btnRainfall) {
            btnRainfall.addEventListener("click", () => {
                // Toggle rainfall layer: attempt to fetch /api/rainfall-map-data
                if (velocityLayer && map.hasLayer(velocityLayer)) {
                    map.removeLayer(velocityLayer);
                }
                // remove other non-rain layers
                ["temperature", "pressure"].forEach((k) => {
                    if (mapLayers[k] && map.hasLayer(mapLayers[k]))
                        map.removeLayer(mapLayers[k]);
                });
                // If layer already present, toggle it off
                if (mapLayers.rainfall && map.hasLayer(mapLayers.rainfall)) {
                    map.removeLayer(mapLayers.rainfall);
                    btnRainfall.classList.remove(
                        "border-blue-500",
                        "bg-blue-50"
                    );
                    return;
                }

                // Load rainfall map data (expects array of {lat,lon,value} or grid + header)
                fetch("/api/rainfall-map-data")
                    .then((r) => {
                        if (!r.ok) throw new Error("No rainfall map API");
                        return r.json();
                    })
                    .then((payload) => {
                        const layer = L.layerGroup();
                        // accept either grid payload or point array
                        const points = Array.isArray(payload)
                            ? payload
                            : payload.data || [];
                        points.forEach((p) => {
                            if (p.lat == null || p.lon == null) return;
                            const v = p.value ?? p.rainfall ?? p.u ?? 0;
                            const color =
                                v > 50
                                    ? "#7f0000"
                                    : v > 25
                                    ? "#d73027"
                                    : v > 10
                                    ? "#fc8d59"
                                    : v > 1
                                    ? "#91bfdb"
                                    : "#4575b4";
                            const c = L.circle([p.lat, p.lon], {
                                radius: 5000,
                                color: color,
                                fillColor: color,
                                fillOpacity: 0.6,
                            });
                            c.bindPopup(`Value: ${v}`);
                            layer.addLayer(c);
                        });
                        mapLayers.rainfall = layer;
                        layer.addTo(map);
                        btnRainfall.classList.add(
                            "border-blue-500",
                            "bg-blue-50"
                        );
                    })
                    .catch((err) => {
                        console.warn("Rainfall map data not available:", err);
                        alert(
                            "Rainfall map not available yet on the server. Backend endpoint `/api/rainfall-map-data` is required."
                        );
                    });
            });
        }

        const btnTemp = document.getElementById("btn-temperature");
        const btnPressure = document.getElementById("btn-pressure");

        function loadScalarLayer(type, btn) {
            // Remove wind if present
            if (velocityLayer && map.hasLayer(velocityLayer))
                map.removeLayer(velocityLayer);
            // remove other scalar layers
            ["rainfall", "temperature", "pressure"].forEach((k) => {
                if (mapLayers[k] && map.hasLayer(mapLayers[k]))
                    map.removeLayer(mapLayers[k]);
            });

            // Toggle off if already present
            if (mapLayers[type] && map.hasLayer(mapLayers[type])) {
                map.removeLayer(mapLayers[type]);
                btn.classList.remove("border-blue-500", "bg-blue-50");
                return;
            }

            fetch(`/api/${type}-map-data`)
                .then((r) => {
                    if (!r.ok) throw new Error(`${type} map API missing`);
                    return r.json();
                })
                .then((payload) => {
                    const layer = L.layerGroup();
                    const points = Array.isArray(payload)
                        ? payload
                        : payload.data || [];
                    points.forEach((p) => {
                        if (p.lat == null || p.lon == null) return;
                        const v = p.value ?? p.temp ?? p.pressure ?? 0;
                        const color =
                            v > 300
                                ? "#7f0000"
                                : v > 280
                                ? "#d73027"
                                : v > 260
                                ? "#fc8d59"
                                : v > 240
                                ? "#91bfdb"
                                : "#4575b4";
                        const c = L.circle([p.lat, p.lon], {
                            radius: 5000,
                            color: color,
                            fillColor: color,
                            fillOpacity: 0.6,
                        });
                        c.bindPopup(`${type}: ${v}`);
                        layer.addLayer(c);
                    });
                    mapLayers[type] = layer;
                    layer.addTo(map);
                    btn.classList.add("border-blue-500", "bg-blue-50");
                })
                .catch((err) => {
                    console.warn(`${type} map data not available:`, err);
                    alert(
                        `${type} map not available yet on the server. Backend endpoint \/api\/${type}-map-data is required.`
                    );
                });
        }

        if (btnTemp) {
            btnTemp.addEventListener("click", () =>
                loadScalarLayer("temperature", btnTemp)
            );
        }

        if (btnPressure) {
            btnPressure.addEventListener("click", () =>
                loadScalarLayer("pressure", btnPressure)
            );
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
