<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WindMapData; // <-- PENTING

class WindMapDataController extends Controller
{
    public function index()
    {
        // Ambil data angin 'current_wind' yang paling baru
        $windData = WindMapData::where('data_type', 'current_wind')
                            ->latest() // Ambil yang terbaru
                            ->first();

        if ($windData) {
            // 'json_data' akan otomatis jadi array/object berkat casting
            // Kita kirim JSON-nya langsung
            return response()->json($windData->json_data);
        }

        return response()->json(['error' => 'Data peta angin tidak ditemukan'], 404);
    }
}