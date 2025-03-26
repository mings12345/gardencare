<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeasonalTipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PusherAuthController;

// Notifications
Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications', [NotificationController::class, 'store']);
// Pusher Auth
Route::post('/pusher/auth', [PusherAuthController::class, 'authenticate']);

Route::get('/seasonal-tips', [SeasonalTipController::class, 'index']);
Route::get('/seasonal-tips/{plantId}/{region}/{season}', [SeasonalTipController::class, 'getTipsByPlantRegionAndSeason']);
// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
// Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// Services Routes
Route::get('/services', [ServiceController::class, 'getServices']); // Get all services

// Bookings Routes
Route::post('/create_booking', [BookingController::class, 'store']); // Create a booking
Route::get('/gardeners/{gardenerId}/bookings', [BookingController::class, 'getGardenerBookings']); // Get bookings for a gardener
Route::get('/service_providers/{serviceProviderId}/bookings', [BookingController::class, 'getServiceProviderBookings']); // Get bookings for a service provider
Route::get('/service_providers', [AuthController::class, 'getServiceProviders']); // Fetch only users with user_type = service provider

// Fetch all gardeners
Route::get('/gardeners', [AuthController::class, 'getGardeners']); // Fetch only users with user_type = gardener

// Pusher Authentication Endpoint
Route::post('/pusher/auth', function (Request $request) {
    // Initialize Pusher
    $pusher = new Pusher(
        env('30c5136a5ba9d5617c54'), // Your Pusher App Key
        env('6e705867285ce08f6d09'), // Your Pusher App Secret
        env('1951216'), // Your Pusher App ID
        [
            'cluster' => env('ap1'), // Your Pusher Cluster
            'useTLS' => true, // Use TLS for secure connection
        ]
    );

    // Get the socket ID and channel name from the request
    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    // Perform any necessary checks to ensure the user is authorized
    // For example, check if the user is logged in or has the correct permissions
    // You can use Sanctum or any other authentication method here

    // Return the authentication token
    return response()->json(
        $pusher->socket_auth($channelName, $socketId)
    );
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
});
// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile/{userId}', [AuthController::class, 'getProfileData']);
});