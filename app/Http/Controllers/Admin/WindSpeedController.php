<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WindSpeedData;

class WindSpeedController extends Controller
{
    public function index()
    {
        $windSpeedData = WindSpeedData::orderBy('month_index', 'asc')->get();

        return view('admin.wind_data', [
            'wind_speed_data' => $windSpeedData
        ]);
    }

    public function edit($id)
    {
        $windSpeed = WindSpeedData::findOrFail($id);
        
        return view('admin.wind_edit', [
            'wind_speed' => $windSpeed
        ]);
    }

    public function update(Request $request, $id)
    {
        $windSpeed = WindSpeedData::findOrFail($id);
        
        $windSpeed->speed_ms = $request->speed_ms;
        $windSpeed->save();

        return redirect()->route('admin.wind_data.index');
    }

    public function destroy($id)
    {
        $windSpeed = WindSpeedData::findOrFail($id);
        $windSpeed->delete();

        return redirect()->route('admin.wind_data.index');
    }
}