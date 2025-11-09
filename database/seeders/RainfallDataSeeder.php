<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RainfallDataSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama jika ada, untuk menghindari duplikat
        DB::table('rainfall_data')->truncate(); 

        $months = [
            ['Jan', 1, 340], ['Feb', 2, 310], ['Mar', 3, 290],
            ['Apr', 4, 200], ['May', 5, 110], ['Jun', 6, 60],
            ['Jul', 7, 45], ['Aug', 8, 30], ['Sep', 9, 55],
            ['Oct', 10, 170], ['Nov', 11, 280], ['Dec', 12, 330]
        ];

        foreach ($months as $month) {
            DB::table('rainfall_data')->insert([
                'month_name' => $month[0],
                'month_index' => $month[1],
                'rainfall_mm' => $month[2],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}