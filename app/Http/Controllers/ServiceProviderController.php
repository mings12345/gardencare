<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ServiceProviderController extends Controller
{
    // List all service providers
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

    // Store the new service provider in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'user_type' => 'service_provider',
        ]);

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service provider added successfully.');
    }

    // Show the form to edit a service provider
    public function edit($id)
    {
        $serviceProvider = User::findOrFail($id);
        return view('admin.edit-service-provider', compact('serviceProvider'));
    }

    // Update the service provider in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        $serviceProvider = User::findOrFail($id);
        $serviceProvider->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service provider updated successfully.');
    }

    // Delete a service provider
    public function destroy($id)
    {
        $serviceProvider = User::findOrFail($id);
        $serviceProvider->delete();

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service provider deleted successfully.');
    }
}
