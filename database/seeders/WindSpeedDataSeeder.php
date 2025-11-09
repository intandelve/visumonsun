<?php

namespace Database\Seeders; // <-- INI PENTING!

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- INI PENTING!

class WindSpeedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('wind_speed_data')->truncate(); // Kosongkan tabel

        $months = [
            ['Jan', 1, 5.1], ['Feb', 2, 5.3], ['Mar', 3, 5.0],
            ['Apr', 4, 4.5], ['May', 5, 4.0], ['Jun', 6, 3.5],
            ['Jul', 7, 3.8], ['Aug', 8, 4.2], ['Sep', 9, 4.7],
            ['Oct', 10, 5.0], ['Nov', 11, 5.2], ['Dec', 12, 5.5]
        ];

        foreach ($months as $month) {
            DB::table('wind_speed_data')->insert([
                'month_name' => $month[0],
                'month_index' => $month[1],
                'speed_ms' => $month[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}