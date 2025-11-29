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

    public function create()
    {
        return view('admin.wind_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month_name' => 'required|string|max:255',
            'speed_ms' => 'required|numeric',
        ]);

        $windSpeed = new WindSpeedData();
        $windSpeed->month_name = $request->month_name;
        $windSpeed->speed_ms = $request->speed_ms;
        
        $months = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6,
            'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];
        
        if (array_key_exists($request->month_name, $months)) {
            $windSpeed->month_index = $months[$request->month_name];
        } else {
             $windSpeed->month_index = 0; 
        }

        $windSpeed->save();

        return redirect()->route('admin.wind_data.index')->with('success', 'Wind speed data added successfully.');
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