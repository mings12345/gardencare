<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Rating; // Changed from Feedback to Rating
use App\Models\User;
use App\Models\Service;
use App\Models\Payment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get total bookings
        $totalBookings = Booking::count();

        // Get booking statuses
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        // Calculate total earnings (sum of all booking prices)
        $totalEarnings = Payment::sum('admin_fee');

        // Count total users
        $totalHomeowners = User::where('user_type', 'homeowner')->count();
        $totalGardeners = User::where('user_type', 'gardener')->count();
        $totalServiceProviders = User::where('user_type', 'service_provider')->count();

        // Get services and ratings
        $services = Service::all();
        $ratings = Rating::with(['booking'])->latest()->take(5)->get(); // Get latest 5 ratings
        $bookings = Booking::all();

        return view('admin.dashboard', compact(
            'totalBookings', 
            'pendingBookings', 
            'completedBookings', 
            'totalEarnings',
            'totalHomeowners',
            'totalGardeners',
            'totalServiceProviders',
            'services',
            'ratings', // Changed from feedbacks to ratings
            'bookings'
        ));
    }

    // Add this new method for managing ratings/feedback
    public function manageRatings()
    {
        $ratings = Rating::with(['booking.gardener', 'booking.homeowner', 'booking.serviceProvider'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manageRatings', [
            'ratings' => $ratings,
            'totalRatings' => Rating::count(),
        ]);
    }
}