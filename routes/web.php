<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RainfallController;
use App\Http\Controllers\Admin\WindSpeedController;
use App\Http\Controllers\Admin\ForecastController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('home');
});

Route::get('/statistics', function () {
    return view('statistics');
});

Route::get('/forecast', function () {
    return view('forecast');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [RainfallController::class, 'index'])->name('dashboard');
    
    Route::get('/rainfall/{id}/edit', [RainfallController::class, 'edit'])->name('rainfall.edit');
    Route::patch('/rainfall/{id}', [RainfallController::class, 'update'])->name('rainfall.update');
    Route::delete('/rainfall/{id}', [RainfallController::class, 'destroy'])->name('rainfall.destroy');

    Route::get('/wind-data', [WindSpeedController::class, 'index'])->name('admin.wind_data.index');
    Route::get('/wind-data/{id}/edit', [WindSpeedController::class, 'edit'])->name('admin.wind_data.edit');
    Route::patch('/wind-data/{id}', [WindSpeedController::class, 'update'])->name('admin.wind_data.update');
    Route::delete('/wind-data/{id}', [WindSpeedController::class, 'destroy'])->name('admin.wind_data.destroy');
    
    Route::get('/forecasts', [ForecastController::class, 'index'])->name('admin.forecasts.index');
    Route::get('/forecasts/{id}/edit', [ForecastController::class, 'edit'])->name('admin.forecasts.edit');
    Route::patch('/forecasts/{id}', [ForecastController::class, 'update'])->name('admin.forecasts.update');
    Route::delete('/forecasts/{id}', [ForecastController::class, 'destroy'])->name('admin.forecasts.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';