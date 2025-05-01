<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GardenerController extends Controller
{
    // Display a list of gardeners
    public function index()
    {
        $gardeners = User::where('user_type', 'gardener')->get();
        return view('admin.manage-gardeners', compact('gardeners'));
    }

    // Show the form to add a new gardener
    public function create()
    {
        return view('admin.add-gardener');
    }

    // Store a new gardener in the database
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8', // Add password validation
        ]);

        // Create a new gardener
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password), // Hash the password
            'user_type' => 'gardener', // Set the user type to 'gardener'
        ]);

        return redirect()->route('admin.manageGardeners')->with('success', 'Gardener added successfully.');
    }

    // Show the details of a specific gardener
    public function show($id)
    {
        $gardener = User::findOrFail($id);
        return view('admin.view-gardener', compact('gardener'));
    }

    // Show the form to edit a specific gardener
    public function edit($id)
    {
        $gardener = User::findOrFail($id);
        return view('admin.edit-gardener', compact('gardener'));
    }

    // Update the specified gardener in the database
    public function update(Request $request, $id)
    {
        $gardener = User::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $gardener->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8', // Password is optional during update
        ]);

        // Update the gardener's details
        $gardener->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => $request->password ? Hash::make($request->password) : $gardener->password, // Update password if provided
        ]);

        return redirect()->route('admin.manageGardeners')->with('success', 'Gardener updated successfully.');
    }

    // Delete the specified gardener from the database
    public function destroy($id)
    {
        $gardener = User::findOrFail($id);
        
        // Delete related bookings first
        $gardener->bookings()->delete();
        
        $gardener->delete();
    
        return redirect()->route('admin.manageGardeners')
            ->with('success', 'Gardener and related bookings deleted successfully.');
    }
}