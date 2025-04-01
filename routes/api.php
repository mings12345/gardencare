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


Route::post('/messages', [MessageController::class, 'sendMessage']);
Route::get('/messages/{user1}/{user2}', [MessageController::class, 'getMessages']);
// Send notification
Route::post('/send_notification', [NotificationController::class, 'sendNotification']);

// Seasonal Tips Routes
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

// Payment Routes
Route::post('/payment/intent', [PaymentController::class, 'createIntent']); // Create payment intent
Route::post('/payment/attach', [PaymentController::class, 'attachMethod']); // Attach payment method
Route::post('/paymongo/webhook', [PaymentController::class, 'handleWebhook']); // PayMongo webhook

// Fetch all gardeners
Route::get('/gardeners', [AuthController::class, 'getGardeners']); // Fetch only users with user_type = gardener

// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile/{userId}', [AuthController::class, 'getProfileData']);
    
    // Protected Payment Routes
    Route::post('/payment/history', [PaymentController::class, 'paymentHistory']);
    // Store FCM token
    Route::post('/store-token', [NotificationController::class, 'storeToken']);

    // Messaging Routes
    
    Route::post('/broadcasting/auth', function (Request $request) {
        $pusher = new Pusher\Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
        
        return response()->json(
            $pusher->socket_auth($request->channel_name, $request->socket_id)
        );
    });
});
