<?php
// File: update_wind.php
// Purpose: Update wind_speed_data table dengan real data dari ERA5

echo "🌬️  Starting wind speed data update from ERA5...\n\n";

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

// 1. Cek file ERA5 JSON ada atau tidak
$jsonPath = public_path('assets/data/era5_wind.json');

if (!File::exists($jsonPath)) {
    die("❌ ERROR: File era5_wind.json tidak ditemukan!\n   Path: $jsonPath\n");
}

echo "✅ File ERA5 ditemukan!\n";

// 2. Baca file JSON
$windData = json_decode(File::get($jsonPath), true);

if (!isset($windData['data']) || empty($windData['data'])) {
    die("❌ ERROR: Format data tidak valid atau kosong!\n");
}

echo "✅ Data berhasil dibaca: " . count($windData['data']) . " data points\n\n";

// 3. Hitung rata-rata wind speed
echo "📊 Menghitung rata-rata wind speed...\n";

$totalSpeed = 0;
$count = count($windData['data']);

foreach ($windData['data'] as $point) {
    $u = $point['u'] ?? 0;
    $v = $point['v'] ?? 0;
    $speed = sqrt($u * $u + $v * $v);
    $totalSpeed += $speed;
}

$averageSpeed = $totalSpeed / $count;
echo "   Rata-rata: " . round($averageSpeed, 2) . " m/s\n\n";

// 4. Update database
echo "🔄 Updating database...\n";

DB::table('wind_speed_data')->truncate();

// Faktor musiman untuk setiap bulan (berdasarkan pola monsun Indonesia)
// SW Monsoon (Nov-Mar): kecepatan tinggi | NE Monsoon (Apr-Oct): kecepatan rendah
$months = [
    ['Jan', 1, 1.15],  // Puncak monsun barat daya
    ['Feb', 2, 1.10],  // Masih monsun kuat
    ['Mar', 3, 1.05],  // Mulai transisi
    ['Apr', 4, 0.95],  // Awal monsun timur
    ['May', 5, 0.85],  // Angin mulai melemah
    ['Jun', 6, 0.80],  // Angin terlemah
    ['Jul', 7, 0.80],  // Masih lemah
    ['Aug', 8, 0.85],  // Mulai menguat
    ['Sep', 9, 0.90],  // Terus naik
    ['Oct', 10, 0.95], // Hampir transisi
    ['Nov', 11, 1.05], // Monsun barat daya mulai
    ['Dec', 12, 1.15]  // Puncak lagi
];

foreach ($months as $month) {
    $monthSpeed = $averageSpeed * $month[2];
    
    DB::table('wind_speed_data')->insert([
        'month_name' => $month[0],
        'month_index' => $month[1],
        'speed_ms' => round($monthSpeed, 2),
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "   ✓ {$month[0]}: " . round($monthSpeed, 2) . " m/s\n";
}

echo "\n✅ wind_speed_data table updated successfully!\n";
echo "📈 Total records: " . DB::table('wind_speed_data')->count() . "\n";

echo "\n✅ SELESAI!  Cek phpMyAdmin untuk lihat perubahan.\n";