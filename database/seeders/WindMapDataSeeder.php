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
            $jsonData = File::get($jsonPath); 

            // Masukkan ke database
            DB::table('wind_map_data')->insert([
                'data_type' => 'current_wind',
                'json_data' => $jsonData, // Simpan seluruh JSON sebagai teks
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            // Jika file tidak ada, beri tahu kami
            $this->command->error("File dummy-wind-data.json tidak ditemukan di public/assets/data/");
        }
    }
}