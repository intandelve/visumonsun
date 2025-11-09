<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WindMapData; // <-- PENTING

class WindMapDataController extends Controller
{
    public function index(Request $request)
    {
        $dataType = $request->query('type', 'current_wind');

        $mapData = WindMapData::where('data_type', $dataType)
                            ->latest()
                            ->first();

        if ($mapData) {
            return response()->json($mapData->json_data);
        }

        return response()->json(['error' => 'Data peta tidak ditemukan'], 404);
    }

}