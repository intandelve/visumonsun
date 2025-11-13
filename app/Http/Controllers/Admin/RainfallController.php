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

    public function edit($id)
    {
        $rainfall = RainfallData::findOrFail($id);
        
        return view('rainfall_edit', [
            'rainfall' => $rainfall
        ]);
    }

    public function update(Request $request, $id)
    {
        $rainfall = RainfallData::findOrFail($id);
        
        $rainfall->rainfall_mm = $request->rainfall_mm;
        $rainfall->save();

        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $rainfall = RainfallData::findOrFail($id);
        $rainfall->delete();

        return redirect()->route('dashboard');
    }
}