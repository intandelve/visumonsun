document.addEventListener("DOMContentLoaded", function () {
    console.log("Website script loaded!");

    // --- FUNGSI UNTUK MENAMPILKAN WIND DIRECTION MARKERS ---
    function calculateWindDirection(u, v) {
        // u = eastward wind, v = northward wind
        // Calculate wind direction in degrees from North (0°)
        const rad = Math.atan2(u, v); // atan2(east, north)
        let degrees = (rad * 180) / Math.PI;
        degrees = (degrees + 360) % 360; // Normalize to 0-360
        return degrees;
    }

    function getWindDirectionName(degrees) {
        const directions = [
            "N",
            "NNE",
            "NE",
            "ENE",
            "E",
            "ESE",
            "SE",
            "SSE",
            "S",
            "SSW",
            "SW",
            "WSW",
            "W",
            "WNW",
            "NW",
            "NNW",
        ];
        const index = Math.round(degrees / 22.5) % 16;
        return directions[index];
    }

    function addWindDirectionMarkers(windData, map) {
        if (!windData || windData.length === 0) return;

        // Create a layer group for wind markers
        if (windDirectionLayer) {
            map.removeLayer(windDirectionLayer);
        }
        windDirectionLayer = L.layerGroup();

        // Sample wind data points (every 10th point to avoid cluttering map)
        const sampleInterval = Math.ceil(Math.sqrt(windData.length / 50)); // ~50 markers

        windData.forEach((point, idx) => {
            if (idx % sampleInterval !== 0) return;
            if (!point.lat || !point.lon || point.u == null || point.v == null)
                return;

            const direction = calculateWindDirection(point.u, point.v);
            const directionName = getWindDirectionName(direction);
            const speed = Math.sqrt(point.u * point.u + point.v * point.v);

            // Create a rotated arrow marker using SVG
            const arrowSvg = `
                <svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                    <g transform="translate(15, 15) rotate(${direction})">
                        <!-- Arrow pointing north -->
                        <polygon points="0,-10 -6,6 -2,6 -2,10 2,10 2,6 6,6" 
                                 fill="#1e40af" stroke="#1e40af" stroke-width="0.5"/>
                    </g>
                </svg>
            `;

            const arrowIcon = L.divIcon({
                html: arrowSvg,
                className: "wind-direction-marker",
                iconSize: [30, 30],
                popupAnchor: [0, -15],
            });

            const marker = L.marker([point.lat, point.lon], {
                icon: arrowIcon,
            });
            marker.bindPopup(`
                <div class="text-sm">
                    <strong>Wind Direction:</strong> ${directionName} (${direction.toFixed(
                1
            )}°)<br/>
                    <strong>Wind Speed:</strong> ${speed.toFixed(2)} m/s
                </div>
            `);
            windDirectionLayer.addLayer(marker);
        });

        windDirectionLayer.addTo(map);
    }

    // Lengkapi header untuk leaflet-velocity jika API tidak lengkap
    function completeVelocityHeader(rawHeader, data, overrides = {}) {
        const header = { ...(rawHeader || {}) };
        // Kumpulkan lat/lon unik dari data poin
        const lats = Array.from(
            new Set(data.map((d) => d.lat).filter((x) => x != null))
        ).sort((a, b) => b - a); // utara→selatan
        const lons = Array.from(
            new Set(data.map((d) => d.lon).filter((x) => x != null))
        ).sort((a, b) => a - b); // barat→timur

        const nx = header.nx || lons.length || 0;
        const ny = header.ny || lats.length || 0;

        const lo1 =
            header.lo1 != null ? header.lo1 : lons.length ? lons[0] : undefined;
        const la1 =
            header.la1 != null ? header.la1 : lats.length ? lats[0] : undefined;

        // Hitung dx/dy dari rentang jika tidak tersedia
        const lonMin = lons.length ? lons[0] : lo1;
        const lonMax = lons.length ? lons[lons.length - 1] : header.lo2 ?? lo1;
        const latMax = lats.length ? lats[0] : la1; // utara
        const latMin = lats.length ? lats[lats.length - 1] : header.la2 ?? la1; // selatan

        const dx =
            header.dx != null
                ? header.dx
                : nx > 1 && lonMin != null && lonMax != null
                ? (lonMax - lonMin) / (nx - 1)
                : 1;
        const dy =
            header.dy != null
                ? header.dy
                : ny > 1 && latMax != null && latMin != null
                ? (latMax - latMin) / (ny - 1)
                : 1;

        // Leaflet-velocity mengharapkan lo2 dan la2
        const lo2 =
            header.lo2 != null
                ? header.lo2
                : lo1 != null && dx != null && nx
                ? lo1 + dx * (nx - 1)
                : undefined;
        const la2 =
            header.la2 != null
                ? header.la2
                : la1 != null && dy != null && ny
                ? la1 - dy * (ny - 1)
                : undefined; // turun ke selatan

        const completed = {
            parameterUnit: "m.s-1",
            refTime: header.refTime || new Date().toISOString(),
            nx,
            ny,
            lo1,
            la1,
            lo2,
            la2,
            dx,
            dy,
            ...overrides,
        };

        return completed;
    }

    // --- LOGIKA UNTUK HALAMAN DASHBOARD (/) ---
    const mapContainer = document.getElementById("map");
    let velocityLayer;
    let windDirectionLayer = null; // Layer untuk wind direction markers
    let currentWindData = null; // Simpan wind data untuk toggle later

    if (mapContainer) {
        console.log("Map container found, initializing map...");

        // Hindari inisialisasi ganda (mis. navigasi/refresh HMR)
        let map = window.__leafletMap || null;
        if (map && typeof map.remove === "function") {
            map.remove();
            map = null;
        }
        // Bersihkan state Leaflet yang tertinggal pada container
        if (mapContainer._leaflet_id) {
            try {
                mapContainer._leaflet_id = null;
            } catch (e) {
                /* ignore */
            }
        }

        // Inisialisasi Leaflet Map (Indonesia)
        map = L.map("map").setView([-2.5489, 118.0149], 5);
        window.__leafletMap = map;

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

                // Build velocity data from either array-of-records [U,V] or point payload
                let velocityData;
                if (
                    Array.isArray(data) &&
                    data[0]?.header &&
                    Array.isArray(data[0]?.data) &&
                    typeof data[0].data[0] === "number"
                ) {
                    // Already in Leaflet-Velocity format: [U-record, V-record]
                    velocityData = data;
                    currentWindData = null; // arrows need points; skip for grid arrays
                } else {
                    // Single object with {header, data:[{lat,lon,u,v}]}
                    const wind = Array.isArray(data) ? data[0] : data;
                    if (!wind || !Array.isArray(wind.data)) {
                        console.error("Wind data format invalid:", wind);
                        return;
                    }

                    // Convert points to grid arrays ordered north→south, west→east
                    const headerU = completeVelocityHeader(
                        wind.header,
                        wind.data,
                        {
                            parameterCategory: 2,
                            parameterNumber: 2,
                            parameterUnit: "m.s-1",
                        }
                    );
                    const headerV = completeVelocityHeader(
                        wind.header,
                        wind.data,
                        {
                            parameterCategory: 2,
                            parameterNumber: 3,
                            parameterUnit: "m.s-1",
                        }
                    );
                    const lats = Array.from(
                        new Set(
                            wind.data.map((p) => p.lat).filter((x) => x != null)
                        )
                    ).sort((a, b) => b - a);
                    const lons = Array.from(
                        new Set(
                            wind.data.map((p) => p.lon).filter((x) => x != null)
                        )
                    ).sort((a, b) => a - b);
                    const nx = headerU.nx || lons.length;
                    const ny = headerU.ny || lats.length;
                    const uArr = new Array(nx * ny);
                    const vArr = new Array(nx * ny);
                    const lonIndex = new Map(
                        lons.map((val, idx) => [val, idx])
                    );
                    const latIndex = new Map(
                        lats.map((val, idx) => [val, idx])
                    );
                    wind.data.forEach((p) => {
                        const li = latIndex.get(p.lat);
                        const gi = lonIndex.get(p.lon);
                        if (li === undefined || gi === undefined) return;
                        const pos = li * nx + gi;
                        uArr[pos] = Number(p.u);
                        vArr[pos] = Number(p.v);
                    });
                    for (let i = 0; i < nx * ny; i++) {
                        if (typeof uArr[i] === "undefined") uArr[i] = 0;
                        if (typeof vArr[i] === "undefined") vArr[i] = 0;
                    }
                    velocityData = [
                        { header: headerU, data: uArr },
                        { header: headerV, data: vArr },
                    ];
                    currentWindData = wind.data; // for arrows toggle
                }

                console.log("Converted velocity data:", velocityData);
                // Helpful debug: show selected header and data length so we can tune scale
                try {
                    const hdr = Array.isArray(velocityData)
                        ? velocityData[0]?.header
                        : null;
                    const len = Array.isArray(velocityData)
                        ? velocityData[0]?.data?.length
                        : null;
                    console.log("Selected wind header:", hdr);
                    console.log("Wind grid length:", len);
                } catch (e) {
                    /* ignore */
                }

                // --- BUAT VELOCITY LAYER ---
                // Use a more conservative default scale; users complained vectors were too large.
                // You can tune `velocityScale` to taste. Typical values: 0.02 - 0.5 depending on zoom.
                const defaultVelocityScale = 0.75;

                if (typeof L.velocityLayer !== "function") {
                    console.error("leaflet-velocity plugin not loaded.");
                }
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

                // --- TAMBAHKAN WIND DIRECTION MARKERS ---
                currentWindData = wind.data; // Simpan data untuk toggle
                addWindDirectionMarkers(wind.data, map);
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

        // Wind Direction Toggle Button
        const btnWindDirectionToggle = document.getElementById(
            "btn-wind-direction-toggle"
        );
        if (btnWindDirectionToggle) {
            btnWindDirectionToggle.addEventListener("click", () => {
                if (windDirectionLayer && map.hasLayer(windDirectionLayer)) {
                    // Hide wind direction markers
                    map.removeLayer(windDirectionLayer);
                    btnWindDirectionToggle.classList.remove(
                        "bg-blue-50",
                        "border-blue-500"
                    );
                    btnWindDirectionToggle.classList.add(
                        "border-gray-300",
                        "bg-white"
                    );
                    btnWindDirectionToggle.innerHTML = `
                        <svg class="w-5 h-5 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Show Wind Arrows</span>
                    `;
                } else if (currentWindData) {
                    // Show wind direction markers
                    addWindDirectionMarkers(currentWindData, map);
                    btnWindDirectionToggle.classList.add(
                        "bg-blue-50",
                        "border-blue-500"
                    );
                    btnWindDirectionToggle.classList.remove(
                        "border-gray-300",
                        "bg-white"
                    );
                    btnWindDirectionToggle.innerHTML = `
                        <svg class="w-5 h-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
                        </svg>
                        <span class="text-sm font-semibold text-blue-600">Hide Wind Arrows</span>
                    `;
                }
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
