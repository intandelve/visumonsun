<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WindMapData; 
use Illuminate\Support\Facades\File;

class WindMapDataController extends Controller
{
    public function index(Request $request)
    {
        $dataType = $request->query('type', 'current_wind');

        $mapData = WindMapData::where('data_type', $dataType)
                            ->latest()
                            ->first();

        if ($mapData) {
            $json = $mapData->json_data;
            if (is_array($json) && isset($json['file'])) {
                $filePath = public_path($json['file']);
                if (File::exists($filePath)) {
                    $contents = File::get($filePath);
                    $decoded = json_decode($contents, true);
                    return response()->json($decoded);
                }
                return response()->json(['error' => 'Referenced wind file not found: ' . $json['file']], 500);
            }

            return response()->json($json);
        }

        return response()->json(['error' => 'Data peta tidak ditemukan'], 404);
    }

}