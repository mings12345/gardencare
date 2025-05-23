<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class WebServiceController extends Controller
{
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:Gardening,Landscaping',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $service = Service::findOrFail($id);
        $service->update($validated);

        return redirect()->route('admin.manageServices')
            ->with('success', 'Service updated successfully.');
    }

    // Delete a service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.manageServices')
            ->with('success', 'Service deleted successfully.');
    }

     public function showUserServices($userId)
    {
        $user = User::findOrFail($userId);
        
        // Verify user is either gardener or service provider
        if (!in_array($user->user_type, ['gardener', 'service_provider'])) {
            abort(404, 'User not found or not authorized to have services');
        }

        // Get all services for this user
        $services = Service::where('user_id', $userId)->get();

        // Transform image paths to full URLs for display
        $services->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('images/services/' . $service->image);
            }
            return $service;
        });

        // Determine which view to return based on user type
        if ($user->user_type === 'gardener') {
            return view('admin.view-gardener', compact('user', 'services'));
        } else {
            return view('admin.view-service-provider', ['serviceProvider' => $user, 'services' => $services]);
        }
    }

    /**
     * Display services for a specific gardener
     */
    public function showGardenerServices($gardenerId)
    {
        $gardener = User::where('id', $gardenerId)
                       ->where('user_type', 'gardener')
                       ->firstOrFail();
        
        $services = Service::where('user_id', $gardenerId)->get();

        // Transform image paths to full URLs for display
        $services->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('images/services/' . $service->image);
            }
            return $service;
        });

        return view('admin.view-gardener', compact('gardener', 'services'));
    }

    /**
     * Display services for a specific service provider
     */
    public function showServiceProviderServices($serviceProviderId)
    {
        $serviceProvider = User::where('id', $serviceProviderId)
                              ->where('user_type', 'service_provider')
                              ->firstOrFail();
        
        $services = Service::where('user_id', $serviceProviderId)->get();

        // Transform image paths to full URLs for display
        $services->transform(function ($service) {
            if ($service->image) {
                $service->image = asset('images/services/' . $service->image);
            }
            return $service;
        });

        return view('admin.view-service-provider', compact('serviceProvider', 'services'));
    }
}
