<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch all bookings with related data
        $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services'])->get();

        // Statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        return view('admin.dashboard', compact('bookings', 'totalBookings', 'pendingBookings', 'completedBookings'));
    }

    public function users()
{
    $users = User::all();
    return view('admin.users', compact('users'));
}
}
