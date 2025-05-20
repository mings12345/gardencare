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
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
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
        'user_type' => 'gardener',
    ];

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $data['profile_image'] = $path;
    }

    User::create($data);

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

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $gardener->id,
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
        'password' => $request->password ? Hash::make($request->password) : $gardener->password,
    ];

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        // Delete old image if it exists
        if ($gardener->profile_image) {
            Storage::delete('public/' . $gardener->profile_image);
        }
        
        // Store the new image
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $data['profile_image'] = $path;
    }

    $gardener->update($data);

    return redirect()->route('admin.manageGardeners')->with('success', 'Gardener updated successfully.');
}

    // Delete the specified gardener from the database
    public function destroy($id)
    {
        $gardener = User::findOrFail($id);
        
        // Check if gardener has bookings
        if ($gardener->bookings()->exists()) {
            return redirect()->route('admin.manageGardeners')
                ->with('error', 'Cannot delete gardener because they have associated bookings.');
        }
        
        $gardener->delete();

        return redirect()->route('admin.manageGardeners')
            ->with('success', 'Gardener deleted successfully.');
    }
}