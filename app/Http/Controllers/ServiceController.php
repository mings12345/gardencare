<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

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
        return view('admin.add-service');
    }

    // Store a new service
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Create a new service
        Service::create($request->all());

        return redirect()->route('admin.manageServices')->with('success', 'Service added successfully.');
    }

    // Show the form to edit a service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.edit-service', compact('service'));
    }

    // Update a service
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Update the service
        $service = Service::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('admin.manageServices')->with('success', 'Service updated successfully.');
    }

    // Delete a service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.manageServices')->with('success', 'Service deleted successfully.');
    }

     // Fetch all services for API
     public function getServices()
     {
         $services = Service::all(); // Fetch all services from the database
         return response()->json([
             'services' => $services, // Return services as JSON
         ]);
     }
}