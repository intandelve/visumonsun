<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Era5WindSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wind_map_data')->truncate(); // Clear existing data

        $jsonPath = public_path('assets/data/era5_wind.json');

        if (File::exists($jsonPath)) {
            // Always store a small pointer object in the DB and keep the full ERA5 JSON on disk.
            // This avoids MySQL "max_allowed_packet" issues and keeps the DB lightweight.
            $dbPayload = ['file' => 'assets/data/era5_wind.json'];
            DB::table('wind_map_data')->insert([
                'data_type' => 'current_wind',
                'json_data' => json_encode($dbPayload),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $this->command->info('Inserted era5_wind.json file pointer into wind_map_data');
        } else {
            $this->command->error('era5_wind.json not found in public/assets/data/');
        }
    }
}
