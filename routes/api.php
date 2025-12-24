<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RainfallDataController;
use App\Http\Controllers\Api\WindSpeedDataController;
use App\Http\Controllers\Api\HistoricalRainfallController;
use App\Http\Controllers\Api\WindMapDataController;
use App\Http\Controllers\Api\ForecastController; // <-- BARIS BARU


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/rainfall-stats', [RainfallDataController::class, 'index']);

Route::get('/wind-speed-stats', [WindSpeedDataController::class, 'index']);

Route::get('/comparison-data', [HistoricalRainfallController::class, 'index']);

Route::get('/wind-map-data', [WindMapDataController::class, 'index']);

Route::get('/seasonal-forecast', [ForecastController::class, 'index']);