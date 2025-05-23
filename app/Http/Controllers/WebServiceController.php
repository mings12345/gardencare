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
}
