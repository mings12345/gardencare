<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Feedback;
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

        // Get services and feedbacks
        $services = Service::all();
        $feedbacks = Feedback::all();
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
            'feedbacks',
            'bookings'
        ));
    }
}
