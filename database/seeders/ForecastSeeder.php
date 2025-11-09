<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForecastSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('forecasts')->truncate(); // Kosongkan tabel

        DB::table('forecasts')->insert([
            'data_type' => 'seasonal_outlook',
            'title' => 'Seasonal Outlook (Next 3 Months)',
            'content' => 'Based on the latest LSTM model run, the upcoming wet season onset for West Java is predicted to be **slightly delayed**, starting around the **second week of December 2025**. Rainfall intensity is expected to be **+10-20% above average (wetter)**, particularly in western and central parts of Indonesia, indicating a potential moderate La Niña influence.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('forecasts')->insert([
            'data_type' => 'monsoon_onset',
            'title' => 'Monsoon Onset Prediction',
            'content' => 'Predicted Start for West Java: Dec 8 - 14, 2025',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}