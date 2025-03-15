<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;

class ServiceRequestController extends Controller
{
    public function index()
    {
        // Fetch all service requests
        $serviceRequests = ServiceRequest::all();

        // Pass the data to the view
        return view('admin.manage-service-requests', compact('serviceRequests'));
    }
}