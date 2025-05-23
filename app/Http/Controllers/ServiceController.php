<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\User;
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
        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/services'), $imageName);
        $validated['image'] = $imageName;
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
    $validated = $request->validate([
        'type' => 'required|in:Gardening,Landscaping',
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240' // 10MB max
    ]);

    $service = Service::findOrFail($id);

    // Handle image upload if new image is provided
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($service->image && Storage::exists('public/images/services/' . $service->image)) {
            Storage::delete('public/images/services/' . $service->image);
        }

        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/services'), $imageName);
        $validated['image'] = $imageName;
    }

    $service->update($validated);

    // Return the updated service with full image URL
    if ($service->image) {
        $service->image = asset('images/services/' . $service->image);
    }

    return response()->json([
        'message' => 'Service updated successfully',
        'service' => $service
    ]);
}


    // Delete a service
    public function destroy($id)
{
    $service = Service::findOrFail($id);

    // Delete associated image if exists
    if ($service->image && file_exists(public_path('images/services/' . $service->image))) {
        unlink(public_path('images/services/' . $service->image));
    }

    $service->delete();

    return response()->json(['message' => 'Service deleted successfully']);
}

    // Fetch all services for API
    public function getServices()
    {
        $services = Service::all();
        return response()->json(['services' => $services]);
    }

    public function getGardeningServices()
    {
        $gardeningServices = Service::where('type', 'Gardening')->get();
        
        // Transform the image paths to full URLs
        $gardeningServices->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('images/services/' . basename($service->image));
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
                $service->image = asset('images/services/' . basename($service->image));
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

      public function getServicesByUser($userId)
{
    $user = User::findOrFail($userId);
    
    if (!in_array($user->user_type, ['gardener', 'service_provider'])) {
        return response()->json([
            'message' => 'Only gardeners and service providers can have services'
        ], 403);
    }

    $services = Service::where('user_id', $userId)->get();
    
    // Transform image paths to full URLs
    $services->transform(function ($service) {
        if ($service->image) {
            $service->image = asset('images/services/' . $service->image);
        }
        return $service;
    });

    return response()->json(['services' => $services]);
}

    // Store a new service with image upload
    public function storeWithImage(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'type' => 'required|in:Gardening,Landscaping',
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
    ]);

    // Verify user is gardener or service provider
    $user = User::findOrFail($validated['user_id']);
    if (!in_array($user->user_type, ['gardener', 'service_provider'])) {
        return response()->json([
            'message' => 'Only gardeners and service providers can add services'
        ], 403);
    }

    // Handle image upload - Store in public/images/services
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/services'), $imageName);
        $validated['image'] = $imageName;
    }

    $service = Service::create($validated);

    // Return the created service with full image URL
    if ($service->image) {
        $service->image = asset('images/services/' . $service->image);
    }

    return response()->json([
        'message' => 'Service created successfully',
        'service' => $service
    ], 201);
}

    public function showUserServices($userId)
    {
        $user = User::findOrFail($userId);
        
        // Check if user is gardener or service provider
        if (!in_array($user->user_type, ['gardener', 'service_provider'])) {
            abort(403, 'Only gardeners and service providers can have services');
        }

        $services = Service::where('user_id', $userId)->get();
        
        // Transform image paths to full URLs
        $services->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('images/services/' . $service->image);
            }
            return $service;
        });

        // Determine which view to return based on user type
        $view = $user->user_type === 'gardener' ? 'admin.view-gardener' : 'admin.view-service-provider';

        return view($view, [
            $user->user_type => $user,
            'services' => $services
        ]);
    }
}