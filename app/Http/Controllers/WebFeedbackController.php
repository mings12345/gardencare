<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebFeedbackController extends Controller
{
    public function manageFeedback()
{
    $feedbacks = Rating::with(['booking.gardener', 'booking.homeowner', 'booking.serviceProvider'])
        ->orderBy('created_at', 'desc')
        ->paginate(10); // 10 items per page

    return view('admin.dashboard', [
        'feedbacks' => $feedbacks,
        // Your other existing dashboard data...
        'totalBookings' => Booking::count(),
        'totalHomeowners' => User::where('user_type', 'homeowner')->count(),
        'totalGardeners' => User::where('user_type', 'gardener')->count(),
        'totalServiceProviders' => User::where('user_type', 'service_provider')->count(),
        'totalEarnings' => Booking::sum('amount'), // Adjust based on your payment system
        'services' => Service::all(),
    ]);
}

public function deleteFeedback($id)
{
    try {
        $feedback = Rating::findOrFail($id);
        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully']);
    } catch (\Exception $e) {
        \Log::error('Failed to delete feedback: ' . $e->getMessage());
        return response()->json(['message' => 'Failed to delete feedback'], 500);
    }
}
}
