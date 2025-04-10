<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GardenerApiController extends Controller
{
    // Get all gardeners
    public function index()
    {
        $gardeners = User::where('user_type', 'gardener')->get();
        return response()->json($gardeners);
    }
    
    // Get a specific gardener's details
    public function show($id)
{
    $gardener = User::with('portfolioImages')->findOrFail($id);
    
    return response()->json([
        'id' => $gardener->id,
        'name' => $gardener->name,
        'email' => $gardener->email,
        'phone' => $gardener->phone,
        'address' => $gardener->address,
        'bio' => $gardener->bio,
        'rating' => 4.8, // Calculate from ratings
        'completed_jobs' => 24, // Count from jobs
        'years_experience' => $gardener->years_experience,
        'portfolio_images' => $gardener->portfolioImages->pluck('image_url')->toArray(),
    ]);
}
}