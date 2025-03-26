<?php

namespace App\Http\Controllers;

use App\Models\SeasonalTip;
use Illuminate\Http\Request;

class SeasonalTipController extends Controller
{
    // Get all seasonal tips
    public function index()
    {
        return SeasonalTip::with('plant')->get();
    }

    // Get tips by region and season
    public function getTipsByPlantRegionAndSeason($plantId, $region, $season)
    {
        return SeasonalTip::where('plant_id', $plantId)
                          ->where('region', $region)
                          ->where('season', $season)
                          ->with('plant')
                          ->get();
    }
}
