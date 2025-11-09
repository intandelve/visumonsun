<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forecast; // <-- PENTING

class ForecastController extends Controller
{
    public function index()
    {
        // Ambil semua data prakiraan
        // Kita gunakan 'keyBy' agar datanya mudah diakses di JS
        $forecasts = Forecast::latest()->get()->keyBy('data_type');

        return response()->json($forecasts);
    }
}