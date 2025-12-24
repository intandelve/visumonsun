<?php

namespace App\Http\Controllers;

use App\Models\RainfallData;
use App\Models\WindSpeedData;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // Ambil data rainfall
        $rainfallData = RainfallData::orderBy('month_index', 'asc')->get();
        $windData = WindSpeedData::orderBy('month_index', 'asc')->get();

        // Hitung statistik
        $totalRainfall = $rainfallData->sum('rainfall_mm');
        $avgRainfall = $rainfallData->avg('rainfall_mm');
        $maxRainfall = $rainfallData->max('rainfall_mm');
        $minRainfall = $rainfallData->min('rainfall_mm');

        $avgWindSpeed = $windData->avg('speed_ms');
        $maxWindSpeed = $windData->max('speed_ms');
        $minWindSpeed = $windData->min('speed_ms');

        $rainfallCount = $rainfallData->count();
        $windCount = $windData->count();

        return view('statistics', [
            'rainfallData' => $rainfallData,
            'windData' => $windData,
            'totalRainfall' => $totalRainfall,
            'avgRainfall' => $avgRainfall,
            'maxRainfall' => $maxRainfall,
            'minRainfall' => $minRainfall,
            'avgWindSpeed' => $avgWindSpeed,
            'maxWindSpeed' => $maxWindSpeed,
            'minWindSpeed' => $minWindSpeed,
            'rainfallCount' => $rainfallCount,
            'windCount' => $windCount,
        ]);
    }
}
