<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;

class GardeningTipController extends Controller
{
    public function getTips(Request $request)
    {
        $month = date('n'); // Current month (1-12)
        $season = ($month >= 5 && $month <= 10) ? 'wet' : 'dry';
        
        $tips = Tip::where('season', $season)
                  ->inRandomOrder()
                  ->limit(3)
                  ->get();
        
        return response()->json([
            'success' => true,
            'season' => $season,
            'tips' => $tips,
        ]);
    }
}