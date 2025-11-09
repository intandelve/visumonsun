<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoricalRainfall; // <-- PENTING

class HistoricalRainfallController extends Controller
{
    public function index()
    {
        // Ambil SEMUA data historis (1998 dan 2023)
        $data = HistoricalRainfall::orderBy('year', 'asc')
                    ->orderBy('month_index', 'asc')
                    ->get();
        return response()->json($data);
    }
}