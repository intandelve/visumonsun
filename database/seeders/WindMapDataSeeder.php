<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WindMapDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wind_map_data')->truncate();

        // Use real ERA5 wind data (large grid: 185x89 = 16,465 points)
        $jsonPath = public_path('assets/data/era5_wind.json');

        if (File::exists($jsonPath)) {
            $jsonString = File::get($jsonPath);
            $jsonData = json_decode($jsonString, true);

            // Convert flat data array to header + data format for Leaflet-Velocity
            $windData = $this->formatWindDataForMap($jsonData['data']);

            DB::table('wind_map_data')->insert([
                'data_type' => 'current_wind',
                'json_data' => json_encode($windData),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->command->info("✅ Wind map data seeded with ERA5 real data!");
        } else {
            $this->command->error("❌ File era5_wind.json not found!");
        }
    }

    /**
     * Convert flat ERA5 data array to Leaflet-Velocity format
     * with header metadata and proper grid structure
     */
    private function formatWindDataForMap($flatData)
    {
        // ERA5 grid dimensions from conversion (185 lon x 89 lat)
        $nx = 185;
        $ny = 89;
        
        // Indonesia bounding box: 95-141°E, -11-6°N
        $lo1 = 95.0;   // West (left)
        $la1 = 6.0;    // North (top)
        $dx = 0.25;    // Resolution in degrees
        $dy = 0.25;
        
        return [
            'header' => [
                'parameterUnit' => 'm.s-1',
                'refTime' => date('c'),
                'nx' => $nx,
                'ny' => $ny,
                'lo1' => $lo1,
                'la1' => $la1,
                'dx' => $dx,
                'dy' => $dy
            ],
            'data' => $flatData  // Already in {u, v} format from ERA5
        ];
    }
}
