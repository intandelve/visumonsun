<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RainfallData;

class RainfallController extends Controller
{
    public function index()
    {
        $rainfallData = RainfallData::orderBy('month_index', 'asc')->get();

        return view('dashboard', [
            'rainfall_data' => $rainfallData
        ]);
    }

    public function create()
    {
        return view('admin.rainfall_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month_name' => 'required|string|max:255',
            'rainfall_mm' => 'required|numeric',
        ]);

        $rainfall = new RainfallData();
        $rainfall->month_name = $request->month_name;
        $rainfall->rainfall_mm = $request->rainfall_mm;
        // Optionally handle month_index if it's required, assuming it's not or is nullable or auto-incremented.
        // If there is logic to auto-calculate month_index from month_name, it should go here.
        // For now, we will leave it as is, or maybe default to 0 or try to parse it.
        
        $months = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6,
            'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];
        
        if (array_key_exists($request->month_name, $months)) {
            $rainfall->month_index = $months[$request->month_name];
        } else {
             // Fallback or user manually entered something else
             $rainfall->month_index = 0; 
        }

        $rainfall->save();

        return redirect()->route('admin.dashboard')->with('success', 'Rainfall data added successfully.');
    }

    public function edit($id)
    {
        $rainfall = RainfallData::findOrFail($id);
        
        return view('rainfall_edit', [
            'rainfall' => $rainfall
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'month_name' => 'required|string|max:255',
            'rainfall_mm' => 'required|numeric',
        ]);

        $rainfall = RainfallData::findOrFail($id);
        
        $rainfall->month_name = $request->month_name;
        $rainfall->rainfall_mm = $request->rainfall_mm;

        $months = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6,
            'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];
        
        if (array_key_exists($request->month_name, $months)) {
            $rainfall->month_index = $months[$request->month_name];
        } else {
             $rainfall->month_index = 0; 
        }

        $rainfall->save();

        return redirect()->route('admin.dashboard')->with('success', 'Rainfall data updated successfully.');
    }

    public function destroy($id)
    {
        $rainfall = RainfallData::findOrFail($id);
        $rainfall->delete();

        return redirect()->route('admin.dashboard');
    }
}