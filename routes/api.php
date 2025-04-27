<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeasonalTipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Broadcast;


// Broadcasting Authentication
Route::post('/broadcasting/auth', function (Request $request) {
    return  Broadcast::auth($request);
})->middleware('auth:sanctum');

// Messaging Routes
Route::post('/messages', [MessageController::class, 'sendMessage']);
Route::get('/messages/{user1}/{user2}', [MessageController::class, 'getMessages']);
Route::get('/messages/unread-counts/{userId}', [MessageController::class, 'getUnreadCounts']);

// Notification Routes
Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth:sanctum');
Route::post('/store-token', [NotificationController::class, 'storeToken']); // Store FCM token
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');

// Seasonal Tips Routes
Route::get('/seasonal-tips', [SeasonalTipController::class, 'index']);
Route::get('/seasonal-tips/{plantId}/{region}/{season}', [SeasonalTipController::class, 'getTipsByPlantRegionAndSeason']);

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']); // Add FCM token update route

// Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// Services Routes
Route::get('/services', [ServiceController::class, 'getServices']); // Get all services
Route::get('/services/gardening', [ServiceController::class, 'getGardeningServices']);



// Payment Routes
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook']);

// Fetch all users by type
Route::get('/gardeners', [AuthController::class, 'getGardeners']); 
Route::get('/service_providers', [AuthController::class, 'getServiceProviders']); // Fetch only users with user_type = service provider
Route::get('/homeowners', [AuthController::class, 'getHomeowners']); 


// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {

    // Bookings Routes
    Route::get('/bookings/count/{userId}', [BookingController::class, 'countBookings']);
    Route::post('/create_booking', [BookingController::class, 'store']); // Create a booking
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('/bookings/{booking}/status', [BookingController::class, 'updateStatus']);
    Route::get('/gardeners/{gardenerId}/bookings', [BookingController::class, 'getGardenerBookings']); // Get bookings for a gardener
    Route::get('/service_providers/{serviceProviderId}/bookings', [BookingController::class, 'getServiceProviderBookings']); // Get bookings for a service provider
    Route::get('/homeowners/{homeownerId}/bookings', [BookingController::class, 'getHomeownerBookings']); // Get bookings for a gardener
    Route::get('/get_pending_bookings/{userId}', [BookingController::class, 'get_pending_bookings']); 
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
   
    Route::post('/update_gcash', [AuthController::class, 'updateGcash']); // Create a booking
    // Profile Routes
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('/profile/{userId}', [AuthController::class, 'getProfileData']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
  
});