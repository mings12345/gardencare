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
use Illuminate\Support\Facades\Response;

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
            'bookings',
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

            public function reports()
        {
            // Get all bookings with related data
            $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'payments'])
                ->orderBy('date', 'desc')
                ->get();

            // Get all ratings with related booking data
            $ratings = Rating::with(['booking.homeowner'])
                ->orderBy('created_at', 'desc')
                ->get();

                // Get data for the last 6 months
    $completedBookingsByMonth = [];
    $pendingBookingsByMonth = [];
    $declinedBookingsByMonth = []; // New array for declined bookings
    $earningsByMonth = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();
        
        $completedBookingsByMonth[] = Booking::where('status', 'completed')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();
            
        $pendingBookingsByMonth[] = Booking::where('status', 'pending')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();
            
        // Add declined bookings count
        $declinedBookingsByMonth[] = Booking::where('status', 'declined')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();
            
        $earningsByMonth[] = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->sum('amount_paid');
    }
            // Get all users
            $users = User::orderBy('created_at', 'desc')->get();

            // Calculate statistics
            $totalBookings = Booking::count();
            $totalEarnings = Payment::sum('amount_paid');
            $averageRating = Rating::avg('rating') ?? 0;

                return view('admin.reports', compact(
                    'bookings',
                    'ratings',
                    'users',
                    'totalBookings',
                    'totalEarnings',
                    'averageRating',
                    'completedBookingsByMonth',
                    'pendingBookingsByMonth',
                    'declinedBookingsByMonth', // Add this
                    'earningsByMonth'
                ));
        }

    public function exportReports(Request $request)
{
    $request->validate([
        'type' => 'required|in:bookings,earnings,ratings,users',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date'
    ]);

    $type = $request->type;
    $fileName = $type . '_report_' . now()->format('Y-m-d') . '.csv';
    
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use ($type, $request) {
        $file = fopen('php://output', 'w');
        
        // Header row
        if ($type === 'bookings') {
            fputcsv($file, ['Booking ID', 'Date', 'Homeowner', 'Service Provider', 'Total Price', 'Status']);
            
            $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider'])
                ->when($request->start_date, fn($q) => $q->where('date', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->where('date', '<=', $request->end_date))
                ->get();
            
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->date->format('M d, Y'),
                    $booking->homeowner->name,
                    $booking->gardener_id ? $booking->gardener->name . ' (Gardener)' : 
                        ($booking->serviceprovider_id ? $booking->serviceProvider->name . ' (Service Provider)' : 'N/A'),
                    '₱' . number_format($booking->total_price, 2),
                    ucfirst($booking->status)
                ]);
            }
        } 
        elseif ($type === 'earnings') {
            fputcsv($file, ['Booking ID', 'Payment Date', 'Amount Paid', 'Admin Fee', 'Provider Earnings', 'Status']);
            
            $payments = Payment::with(['booking'])
                ->when($request->start_date, fn($q) => $q->where('payment_date', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->where('payment_date', '<=', $request->end_date))
                ->get();
            
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->booking_id,
                    $payment->payment_date->format('M d, Y'),
                    '₱' . number_format($payment->amount_paid, 2),
                    '₱' . number_format($payment->admin_fee, 2),
                    '₱' . number_format($payment->amount_paid - $payment->admin_fee, 2),
                    ucfirst($payment->payment_status)
                ]);
            }
        }
        elseif ($type === 'ratings') {
            fputcsv($file, ['Booking ID', 'Date', 'Rating', 'Feedback', 'Rated By']);
            
            $ratings = Rating::with(['booking.homeowner'])
                ->when($request->start_date, fn($q) => $q->where('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->where('created_at', '<=', $request->end_date))
                ->get();
            
            foreach ($ratings as $rating) {
                fputcsv($file, [
                    $rating->booking_id,
                    $rating->created_at->format('M d, Y'),
                    $rating->rating . '/5',
                    $rating->feedback ?? 'No feedback',
                    $rating->booking->homeowner->name
                ]);
            }
        }
        elseif ($type === 'users') {
            fputcsv($file, ['User ID', 'Name', 'Email', 'Phone', 'Type', 'Registration Date']);
            
            $users = User::query()
                ->when($request->start_date, fn($q) => $q->where('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->where('created_at', '<=', $request->end_date))
                ->get();
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $user->user_type)),
                    $user->created_at->format('M d, Y')
                ]);
            }
        }
        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}
}