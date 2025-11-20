<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Era5WindSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wind_map_data')->truncate();

        $jsonPath = public_path('assets/data/era5_wind.json');

        if (File::exists($jsonPath)) {
            // If file is very large, avoid inserting full JSON into DB (avoids max_allowed_packet errors).
            $size = File::size($jsonPath);
            $threshold = 5 * 1024 * 1024; // 5 MB threshold (adjust as needed)

            if ($size > $threshold) {
                // Store a small pointer object in the DB and keep the full JSON on disk.
                $dbPayload = [ 'file' => 'assets/data/era5_wind.json' ];
                DB::table('wind_map_data')->insert([
                    'data_type' => 'current_wind',
                    'json_data' => json_encode($dbPayload),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->info("era5_wind.json is large ({$size} bytes). Inserted file pointer into DB.");
            } else {
                $jsonString = File::get($jsonPath);
                $jsonData = json_decode($jsonString, true);

                DB::table('wind_map_data')->insert([
                    'data_type' => 'current_wind',
                    'json_data' => json_encode($jsonData),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->command->info('Imported era5_wind.json into wind_map_data');
            }
        } else {
            $this->command->error('era5_wind.json not found in public/assets/data/');
        }
    }
}
