<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalRainfallSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('historical_rainfall_data')->truncate(); // Kosongkan tabel

        $data1998 = [
            ['Jan', 1, 450], ['Feb', 2, 420], ['Mar', 3, 380],
            ['Apr', 4, 250], ['May', 5, 120], ['Jun', 6, 60],
            ['Jul', 7, 50], ['Aug', 8, 30], ['Sep', 9, 60],
            ['Oct', 10, 180], ['Nov', 11, 300], ['Dec', 12, 430]
        ];

        $data2023 = [
            ['Jan', 1, 310], ['Feb', 2, 290], ['Mar', 3, 270],
            ['Apr', 4, 190], ['May', 5, 100], ['Jun', 6, 50],
            ['Jul', 7, 40], ['Aug', 8, 25], ['Sep', 9, 50],
            ['Oct', 10, 150], ['Nov', 11, 260], ['Dec', 12, 310]
        ];

        foreach ($data1998 as $month) {
            DB::table('historical_rainfall_data')->insert([
                'year' => 1998,
                'month_name' => $month[0],
                'month_index' => $month[1],
                'rainfall_mm' => $month[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        foreach ($data2023 as $month) {
            DB::table('historical_rainfall_data')->insert([
                'year' => 2023,
                'month_name' => $month[0],
                'month_index' => $month[1],
                'rainfall_mm' => $month[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}