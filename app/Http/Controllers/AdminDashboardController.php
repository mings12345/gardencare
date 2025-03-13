<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider'])->get();

        return view('admin.dashboard', compact('totalBookings', 'pendingBookings', 'completedBookings', 'bookings'));
    }
}