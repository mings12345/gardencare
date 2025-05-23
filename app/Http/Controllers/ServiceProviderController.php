<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|regex:/\.com$/|unique:users,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'password' => Hash::make($request->password),
        'user_type' => 'service_provider',
    ];

    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $data['profile_image'] = $path;
    }

    User::create($data);

    return redirect()->route('admin.manageServiceProviders')->with('success', 'Service provider added successfully.');
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

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|regex:/\.com$/|unique:users,email,' . $serviceProvider->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => $request->password ? Hash::make($request->password) : $serviceProvider->password,
        ];

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($serviceProvider->profile_image) {
                Storage::delete('public/' . $serviceProvider->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        $serviceProvider->update($data);

        return redirect()->route('admin.manageServiceProviders')->with('success', 'Service provider updated successfully.');
    }
    // Delete the specified service provider from the database
            public function destroy($id)
        {
            $serviceProvider = User::findOrFail($id);
            $serviceProvider->delete();

            return redirect()->route('admin.manageServiceProviders')->with('success', 'Service Provider deleted successfully.');
        }
}