<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    // Get all plants
    public function index()
    {
        return response()->json(Plant::all(), 200);
    }

    // Get a specific plant
    public function show($id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            return response()->json(['message' => 'Plant not found'], 404);
        }

        return response()->json($plant, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'watering_frequency' => 'required|string',
            'sunlight' => 'required|string',
            'soil' => 'required|string',
            'fertilizer' => 'required|string',
            'common_problems' => 'required|string',
        ]);

        $plant = Plant::create($validated);

        return response()->json($plant, 201);
    }
}
