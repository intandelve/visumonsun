<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // <-- Kita pakai 'File'

class WindMapDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('wind_map_data')->truncate(); // Kosongkan tabel

        // Path ke file JSON dummy kita di folder 'public'
        $jsonPath = public_path('assets/data/dummy-wind-data.json');

        // Pastikan file itu ada
        if (File::exists($jsonPath)) {

            // Baca isi file sebagai string
            $jsonString = File::get($jsonPath);
            // DECODE string JSON menjadi array PHP
            $jsonData = json_decode($jsonString, true);

            // Masukkan ke database
            DB::table('wind_map_data')->insert([
                'data_type' => 'current_wind',
                // Pastikan data di-encode kembali ke JSON saat disimpan
                'json_data' => json_encode($jsonData),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            // Jika file tidak ada, beri tahu kami
            $this->command->error("File dummy-wind-data.json tidak ditemukan di public/assets/data/");
        }
    }
}