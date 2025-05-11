<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // Display all services
    public function index()
    {
        $services = Service::all();
        return view('admin.manage-services', compact('services'));
    }

    // Show the form to add a new service
    public function create()
    {
        $serviceTypes = ['Gardening', 'Landscaping'];
        return view('admin.add-service', compact('serviceTypes'));
    }

    // Store a new service
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:Gardening,Landscaping',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // 2MB max
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/services');
            $validated['image'] = str_replace('public/', '', $imagePath);
        }

        Service::create($validated);

        return redirect()->route('admin.manageServices')
            ->with('success', 'Service added successfully.');
    }

    // Show the form to edit a service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $serviceTypes = ['Gardening', 'Landscaping'];
        return view('admin.edit-service', compact('service', 'serviceTypes'));
    }

    // Update a service
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $validated = $request->validate([
            'type' => 'required|in:Gardening,Landscaping',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                Storage::delete('public/' . $service->image);
            }
            
            $imagePath = $request->file('image')->store('public/services');
            $validated['image'] = str_replace('public/', '', $imagePath);
        }

        $service->update($validated);

        return redirect()->route('admin.manageServices')
            ->with('success', 'Service updated successfully.');
    }

    // Delete a service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Delete associated image
        if ($service->image) {
            Storage::delete('public/' . $service->image);
        }
        
        $service->delete();

        return redirect()->route('admin.manageServices')
            ->with('success', 'Service deleted successfully.');
    }

    // Fetch all services for API
    public function getServices()
    {
        $services = Service::all();
        
        // Transform image paths to full URLs
        $services->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('storage/services/' . basename($service->image));
            }
            return $service;
        });

        return response()->json(['services' => $services]);
    }

    public function getGardeningServices()
    {
        $gardeningServices = Service::where('type', 'Gardening')->get();
        
        // Transform the image paths to full URLs
        $gardeningServices->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('storage/services/' . basename($service->image));
            }
            return $service;
        });

        return response()->json(['services' => $gardeningServices]);
    }

    public function getLandscapingServices()
    {
        $landscapingServices = Service::where('type', 'Landscaping')->get();
        
        // Transform the image paths to full URLs
        $landscapingServices->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('storage/services/' . basename($service->image));
            }
            return $service;
        });

        return response()->json(['services' => $landscapingServices]);
    }

    public function countServices()
    {
        $gardeningCount = Service::where('type', 'Gardening')->count();
        $landscapingCount = Service::where('type', 'Landscaping')->count();

        return response()->json([
            'gardening_count' => $gardeningCount,
            'landscaping_count' => $landscapingCount,
            'total_services' => $gardeningCount + $landscapingCount
        ]);
    }
}