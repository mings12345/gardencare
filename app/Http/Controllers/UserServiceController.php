<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserServiceController extends Controller
{
    // Get all services for a specific user type
    public function getByUserType($userType)
    {
        $validTypes = ['gardener', 'service_provider'];
        
        if (!in_array($userType, $validTypes)) {
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $services = User::where('user_type', $userType)
            ->whereNotNull('services')
            ->get()
            ->flatMap(function ($user) {
                return collect(json_decode($user->services, true))
                    ->map(function ($service) use ($user) {
                        return $this->formatService($service, $user);
                    });
            });

        return response()->json(['services' => $services]);
    }

    // Add a new service to a user's services
    public function store(Request $request)
    {
        try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Gardening,Landscaping',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $user = User::findOrFail($validated['user_id']);

        $serviceData = [
            'type' => $validated['type'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/user-services');
            $serviceData['image'] = str_replace('public/', '', $path);
        }

        $services = $user->services ? json_decode($user->services, true) : [];
        $services[] = $serviceData;
        $user->services = json_encode($services);
        $user->save();

        return response()->json([
            'success' => true,
            'service' => $this->formatService($serviceData, $user)
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    }
    }

    // Format service data consistently
    private function formatService(array $service, User $user): array
    {
        return [
            'id' => 'user_'.$user->id.'_'.md5(json_encode($service)),
            'user_id' => $user->id,
            'type' => $service['type'],
            'name' => $service['name'],
            'price' => $service['price'],
            'description' => $service['description'] ?? null,
            'image' => isset($service['image']) ? asset('storage/'.$service['image']) : null,
            'created_at' => $service['created_at'] ?? now()->toDateTimeString(),
            'updated_at' => $service['updated_at'] ?? now()->toDateTimeString(),
            'user_name' => $user->name,
            'is_system' => false
        ];
    }
}