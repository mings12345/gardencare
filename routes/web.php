<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GardenerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\HomeownerController;

Route::get('/bookings', [BookingController::class, 'index']);
Route::get('admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/manage-bookings', [BookingController::class, 'index'])->name('admin.manageBookings');
    Route::get('admin/manage-users', [AuthController::class, 'index'])->name('admin.manageUsers');
    Route::get('admin/manage-service-requests', [ServiceRequestController::class, 'index'])->name('admin.manageServiceRequests');
    
    // Homeowner Routes
    Route::get('admin/manage-homeowners', [HomeownerController::class, 'index'])->name('admin.manageHomeowners');
    Route::get('admin/add-homeowner', [HomeownerController::class, 'create'])->name('admin.addHomeowner');
    Route::post('admin/store-homeowner', [HomeownerController::class, 'store'])->name('admin.storeHomeowner');
    Route::get('admin/view-homeowner/{id}', [HomeownerController::class, 'show'])->name('admin.viewHomeowner');
    Route::get('admin/edit-homeowner/{id}', [HomeownerController::class, 'edit'])->name('admin.editHomeowner');
    Route::put('admin/update-homeowner/{id}', [HomeownerController::class, 'update'])->name('admin.updateHomeowner');
    Route::delete('admin/delete-homeowner/{id}', [HomeownerController::class, 'destroy'])->name('admin.deleteHomeowner');
    
    Route::get('admin/manage-services', [ServiceController::class, 'index'])->name('admin.manageServices');
    Route::get('admin/add-service', [ServiceController::class, 'create'])->name('admin.addService');
    Route::post('admin/add-service', [ServiceController::class, 'store'])->name('admin.addService.store');
    Route::get('admin/edit-service/{id}', [ServiceController::class, 'edit'])->name('admin.editService');
    Route::post('admin/edit-service/{id}', [ServiceController::class, 'update'])->name('admin.editService.update');
    Route::delete('admin/delete-service/{id}', [ServiceController::class, 'destroy'])->name('admin.deleteService');
    
    Route::get('admin/manage-feedback', [FeedbackController::class, 'index'])->name('admin.manageFeedback');

    // Gardener Routes
    Route::get('admin/manage-gardeners', [GardenerController::class, 'index'])->name('admin.manageGardeners');
    Route::get('admin/add-gardener', [GardenerController::class, 'create'])->name('admin.addGardener');
    Route::post('admin/store-gardener', [GardenerController::class, 'store'])->name('admin.storeGardener');
    Route::get('admin/view-gardener/{id}', [GardenerController::class, 'show'])->name('admin.viewGardener');
    Route::get('admin/edit-gardener/{id}', [GardenerController::class, 'edit'])->name('admin.editGardener');
    Route::put('admin/update-gardener/{id}', [GardenerController::class, 'update'])->name('admin.updateGardener');
    Route::delete('admin/delete-gardener/{id}', [GardenerController::class, 'destroy'])->name('admin.deleteGardener');

        // Service Provider Routes
    Route::get('admin/manage-service-providers', [ServiceProviderController::class, 'index'])->name('admin.manageServiceProviders');
    Route::get('admin/add-service-provider', [ServiceProviderController::class, 'create'])->name('admin.addServiceProvider');
    Route::post('admin/store-service-provider', [ServiceProviderController::class, 'store'])->name('admin.storeServiceProvider');
    Route::get('admin/view-service-provider/{id}', [ServiceProviderController::class, 'show'])->name('admin.viewServiceProvider');
    Route::get('admin/edit-service-provider/{id}', [ServiceProviderController::class, 'edit'])->name('admin.editServiceProvider');
    Route::put('admin/update-service-provider/{id}', [ServiceProviderController::class, 'update'])->name('admin.updateServiceProvider');
    Route::delete('admin/delete-service-provider/{id}', [ServiceProviderController::class, 'destroy'])->name('admin.deleteServiceProvider');
    
    
    Route::get('admin/generate-report', [ReportController::class, 'generateReport'])->name('admin.generateReport');
});

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/update-app', function () {
    Artisan::call('update-app');
    return "Successfully Updated";
});

Route::get('/reset-app', function () {
    Artisan::call('migrate:fresh --seed');
    Artisan::call('optimize');
    return "Successfully Reseted";
});

require __DIR__.'/auth.php';
