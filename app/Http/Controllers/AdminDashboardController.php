<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Rating;
use App\Models\User;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $ratings = Rating::with(['booking'])->latest()->take(5)->get();
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
            'ratings',
            'bookings'
        ));
    }

    // Add this new method for managing ratings/feedback
    public function manageRatings()
    {
        $ratings = Rating::with(['booking.gardener', 'booking.homeowner', 'booking.serviceProvider'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manage-ratings', [
            'ratings' => $ratings,
            'totalRatings' => Rating::count(),
        ]);
    }

    /**
     * Show the admin profile page
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the admin profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Logout the admin user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }
}