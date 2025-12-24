<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RainfallController;
use App\Http\Controllers\Admin\WindSpeedController;
use App\Http\Controllers\Admin\ForecastController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;

//login
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
    return redirect()->route('login');
})->name('root');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    Route::get('/forecast', function () {
        return view('forecast');
    })->name('forecast');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');

    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('user_dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController:: class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [RainfallController::class, 'index'])->name('admin.dashboard');

    // Rainfall CRUD
    Route::get('/rainfall/create', [RainfallController::class, 'create'])->name('rainfall.create');
    Route::post('/rainfall', [RainfallController::class, 'store'])->name('rainfall.store');
    Route::get('/rainfall/{id}/edit', [RainfallController::class, 'edit'])->name('rainfall.edit');
    Route::patch('/rainfall/{id}', [RainfallController::class, 'update'])->name('rainfall.update');
    Route::delete('/rainfall/{id}', [RainfallController::class, 'destroy'])->name('rainfall.destroy');

    // Wind Data CRUD
    Route::get('/wind-data', [WindSpeedController::class, 'index'])->name('admin.wind_data.index');
    Route::get('/wind-data/create', [WindSpeedController::class, 'create'])->name('admin.wind_data.create');
    Route::post('/wind-data', [WindSpeedController::class, 'store'])->name('admin.wind_data.store');
    Route::get('/wind-data/{id}/edit', [WindSpeedController::class, 'edit'])->name('admin.wind_data.edit');
    Route::patch('/wind-data/{id}', [WindSpeedController::class, 'update'])->name('admin.wind_data.update');
    Route::delete('/wind-data/{id}', [WindSpeedController::class, 'destroy'])->name('admin.wind_data.destroy');

    // Forecast CRUD
    Route::get('/forecasts', [ForecastController:: class, 'index'])->name('admin.forecasts.index');
    Route::get('/forecasts/create', [ForecastController::class, 'create'])->name('admin.forecasts.create');
    Route::post('/forecasts', [ForecastController:: class, 'store'])->name('admin.forecasts.store');
    Route::get('/forecasts/{id}/edit', [ForecastController::class, 'edit'])->name('admin.forecasts.edit');
    Route::patch('/forecasts/{id}', [ForecastController::class, 'update'])->name('admin.forecasts.update');
    Route::delete('/forecasts/{id}', [ForecastController::class, 'destroy'])->name('admin.forecasts.destroy');

    // User Management CRUD
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController:: class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';