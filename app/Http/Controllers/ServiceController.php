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
    
    public function index()
    {
        $services = Service::all();
        return view('admin.manage-services', compact('services'));
    }
    // Show the form to edit a service
    public function edit($id)
    {
        $service = Service::findOrFail($id); // Find the service by ID
        return view('admin.edit-service', compact('service')); // Pass the service to the view
    }

    // Update the service in the database
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Find the service by ID
        $service = Service::findOrFail($id);

        // Update the service
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // Redirect with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Service updated successfully.');
    }
    
    // Delete a service
    public function destroy($id)
    {
        // Find the service by ID
        $service = Service::findOrFail($id);

        // Delete the service
        $service->delete();

        // Redirect with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Service deleted successfully.');
    }
}