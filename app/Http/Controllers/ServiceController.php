<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Get all services
    public function getServices()
    {
        $services = Service::all();

        return response()->json([
            'services' => $services,
        ]);
    }
}