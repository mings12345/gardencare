<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
// ✅ Add route to fetch all gardeners
Route::get('/gardeners', [AuthController::class, 'getGardeners']); // Fetch only users with user_type = gardener

// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Fetch profile data
    Route::get('/profile/{userId}', [AuthController::class, 'getProfileData']);

});
