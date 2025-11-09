<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RainfallDataController;
use App\Http\Controllers\Api\WindSpeedDataController;
use App\Http\Controllers\Api\HistoricalRainfallController;
use App\Http\Controllers\Api\WindMapDataController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API #1
Route::get('/rainfall-stats', [RainfallDataController::class, 'index']);

// API #2
Route::get('/wind-speed-stats', [WindSpeedDataController::class, 'index']);

// API #3 (BARU)
Route::get('/comparison-data', [HistoricalRainfallController::class, 'index']);

Route::get('/wind-map-data', [WindMapDataController::class, 'index']);