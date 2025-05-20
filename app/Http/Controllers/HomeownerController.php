<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'address' => $request->address,
        'user_type' => 'homeowner',
    ];

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $data['profile_image'] = $path;
    }

    User::create($data);

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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($homeowner->profile_image) {
                Storage::delete('public/' . $homeowner->profile_image);
            }
            
            // Store the new image
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        // Update the homeowner's details
        $homeowner->update($data);

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