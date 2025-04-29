<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPlant;

class UserPlantController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'plant_id' => 'required|exists:plants,id',
    ]);

    $userId = auth()->id(); // Assuming Sanctum auth

    $userPlant = UserPlant::create([
        'user_id' => $userId,
        'plant_id' => $request->plant_id,
    ]);

    return response()->json($userPlant->load('plant'), 201);
}

}
