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

        // Reference the generated ERA5 JSON file directly for API controller to read
        $jsonRelPath = 'assets/data/era5_wind.json';
        $jsonPath = public_path($jsonRelPath);

        if (File::exists($jsonPath)) {
            DB::table('wind_map_data')->insert([
                'data_type' => 'current_wind',
                'json_data' => json_encode(['file' => $jsonRelPath]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info("✅ Wind map data seeded (file reference): {$jsonRelPath}");
        } else {
            $this->command->error("❌ File era5_wind.json not found at public/{$jsonRelPath}. Run converter first.");
        }
    }
    // No conversion here; we serve the file referenced above directly
}
