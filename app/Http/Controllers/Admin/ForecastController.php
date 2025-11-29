<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forecast;

class ForecastController extends Controller
{
    public function index()
    {
        $forecasts = Forecast::orderBy('id', 'desc')->get();

        return view('admin.forecasts_index', [
            'forecasts' => $forecasts
        ]);
    }

    public function create()
    {
        return view('admin.forecasts_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $forecast = new Forecast();
        $forecast->data_type = $request->data_type;
        $forecast->title = $request->title;
        $forecast->content = $request->content;
        $forecast->save();

        return redirect()->route('admin.forecasts.index')->with('success', 'Forecast created successfully.');
    }

    public function edit($id)
    {
        $forecast = Forecast::findOrFail($id);
        
        return view('admin.forecasts_edit', [
            'forecast' => $forecast
        ]);
    }

    public function update(Request $request, $id)
    {
        $forecast = Forecast::findOrFail($id);
        
        $forecast->title = $request->title;
        $forecast->content = $request->content;
        $forecast->save();

        return redirect()->route('admin.forecasts.index');
    }

    public function destroy($id)
    {
        $forecast = Forecast::findOrFail($id);
        $forecast->delete();

        return redirect()->route('admin.forecasts.index');
    }
}