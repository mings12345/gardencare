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
use App\Http\Controllers\RatingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserPlantController;
use App\Http\Controllers\UserServiceController; 


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
Route::get('/services/landscaping', [ServiceController::class, 'getLandscapingServices']);
Route::get('/services/count', [ServiceController::class, 'countServices']);



Route::get('/plants', [PlantController::class, 'index']);
Route::get('/plants/{id}', [PlantController::class, 'show']);
Route::post('/plants', [PlantController::class, 'store']);

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
      Route::get('/services/user/{userId}', [ServiceController::class, 'getServicesByUser']);

    Route::post('/services', [ServiceController::class, 'storeWithImage']);
    
    Route::get('/bookings/by-date-range/{userId}', [BookingController::class, 'getBookingsByDateRange']);
    Route::get('bookings/all/{userId}', [BookingController::class, 'getAllBookings']);
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
    Route::get('/earnings/summary', [BookingController::class, 'getEarningsSummary']);
    Route::get('/get_total_earnings', [BookingController::class, 'getTotalEarnings']);
   
    Route::post('/update_account', [AuthController::class, 'updateAccount']); // Create a booking
    // Profile Routes
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('/profile/{userId}', [AuthController::class, 'getProfileData']);
    Route::post('bookings/{bookingId}/rate', [RatingController::class, 'submitRating']);
    Route::get('/ratings', [RatingController::class, 'index']);

    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::post('/cash-in', [WalletController::class, 'cashIn']);
        Route::post('/withdraw', [WalletController::class, 'withdraw']);
    });
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Seasonal Tips
    Route::post('/user/plants', [UserPlantController::class, 'store']);
  
});