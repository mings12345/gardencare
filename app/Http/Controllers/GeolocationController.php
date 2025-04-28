<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tip;

class GeolocationController extends Controller
{
    public function getTipsByLocation(Request $request)
    {
        // Validate latitude and longitude
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        // Determine season based on Philippine geography
        $season = $this->determinePhilippineSeason($lat, $lng);

        // Get tips for the determined season
        $tips = Tip::where('season', $season)
                 ->inRandomOrder()
                 ->limit(3)
                 ->get();

        return response()->json([
            'season' => $season,
            'location' => ['lat' => $lat, 'lng' => $lng],
            'tips' => $tips
        ]);
    }

    private function determinePhilippineSeason(float $lat, float $lng): string
    {
        // Philippine-specific season logic
        $month = now()->month;
        
        // Northern regions (e.g., Batanes) have slightly different patterns
        $isNorthernRegion = $lat > 16.0;
        
        // Wet season: May to October (extended in some areas)
        if ($month >= 5 && $month <= 10) {
            return 'wet';
        }
        
        // Special case for eastern coastal areas (earlier rains)
        if ($lng > 125.0 && $month >= 4) {
            return 'wet';
        }
        
        return 'dry';
    }
}