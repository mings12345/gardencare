<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    // Display a list of service providers
    public function index()
    {
        $serviceProviders = User::where('user_type', 'service_provider')->get();
        return view('admin.manage-service-providers', compact('serviceProviders'));
    }

    // Show the form to add a new service provider
    public function create()
    {
        return view('admin.add-service-provider');
    }

    // Store a new service provider in the database
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Create a new service provider
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_type' => 'service_provider', // Set the user type to 'service_provider'
        ]);

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service Provider added successfully.');
    }

    // Show the details of a specific service provider
    public function show($id)
    {
        $serviceProvider = User::findOrFail($id);
        return view('admin.view-service-provider', compact('serviceProvider'));
    }

    // Show the form to edit a specific service provider
    public function edit($id)
    {
        $serviceProvider = User::findOrFail($id);
        return view('admin.edit-service-provider', compact('serviceProvider'));
    }

    // Update the specified service provider in the database
    public function update(Request $request, $id)
    {
        $serviceProvider = User::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $serviceProvider->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Update the service provider's details
        $serviceProvider->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service Provider updated successfully.');
    }

    // Delete the specified service provider from the database
    public function destroy($id)
    {
        $serviceProvider = User::findOrFail($id);
        $serviceProvider->delete();

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service Provider deleted successfully.');
    }
}