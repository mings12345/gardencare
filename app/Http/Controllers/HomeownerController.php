<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeownerController extends Controller
{
    // Show the form to add a new homeowner
    public function create()
    {
        return view('admin.add-homeowner');
    }

    // Store a new homeowner in the database
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Create a new homeowner
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_type' => 'homeowner', // Set the user type to 'homeowner'
        ]);

        return redirect()->route('admin.manageHomeowners')->with('success', 'Homeowner added successfully.');
    }

    // List all homeowners
    public function index()
    {
        $homeowners = User::where('user_type', 'homeowner')->get();
        return view('admin.manage-homeowners', compact('homeowners'));
    }

    // Show the details of a specific homeowner
    public function show($id)
    {
        $homeowner = User::findOrFail($id);
        return view('admin.view-homeowner', compact('homeowner'));
    }

    // Show the form to edit a specific homeowner
    public function edit($id)
    {
        $homeowner = User::findOrFail($id);
        return view('admin.edit-homeowner', compact('homeowner'));
    }

    // Update the specified homeowner in the database
    public function update(Request $request, $id)
    {
        $homeowner = User::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $homeowner->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Update the homeowner's details
        $homeowner->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.manageHomeowners')->with('success', 'Homeowner updated successfully.');
    }

    // Delete the specified homeowner from the database
    public function destroy($id)
    {
        $homeowner = User::findOrFail($id);
        $homeowner->delete();

        return redirect()->route('admin.manageHomeowners')->with('success', 'Homeowner deleted successfully.');
    }
}