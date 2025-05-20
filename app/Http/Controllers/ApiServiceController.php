<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiServiceController extends Controller
{

        public function store(Request $request)
        {
            $validated = $request->validate([
                'type' => 'required|in:Landscaping,Gardening',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Add the user_id to the validated data
            $validated['user_id'] = auth()->id();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('services', 'public');
                $validated['image'] = $path;
            }

            $service = Service::create($validated);

            return response()->json([
                'message' => 'Service created successfully',
                'service' => $service
            ], 201);
        }
}