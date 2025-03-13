<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GardenerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceProviderController;


Route::get('/bookings', [BookingController::class, 'index']);
Route::get('admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/manage-bookings', [BookingController::class, 'index'])->name('admin.manageBookings');
    Route::get('admin/manage-users', [AuthController::class, 'index'])->name('admin.manageUsers');
    Route::get('admin/manage-services', [ServiceController::class, 'index'])->name('admin.manageServices');
    Route::get('admin/manage-feedback', [FeedbackController::class, 'index'])->name('admin.manageFeedback');
    Route::get('admin/manage-gardeners', [GardenerController::class, 'index'])->name('admin.manageGardeners');
    Route::get('admin/manage-service-providers', [ServiceProviderController::class, 'index'])->name('admin.manageServiceProviders');
    Route::get('admin/add-service-provider', [ServiceProviderController::class, 'create'])->name('admin.addServiceProvider');
    Route::post('admin/add-service-provider', [ServiceProviderController::class, 'store'])->name('admin.addServiceProvider.store');
    Route::get('admin/edit-service-provider/{id}', [ServiceProviderController::class, 'edit'])->name('admin.editServiceProvider');
    Route::post('admin/edit-service-provider/{id}', [ServiceProviderController::class, 'update'])->name('admin.editServiceProvider.update');
    Route::delete('admin/delete-service-provider/{id}', [ServiceProviderController::class, 'destroy'])->name('admin.deleteServiceProvider');
    Route::get('admin/add-service', [ServiceController::class, 'create'])->name('admin.addService');
    Route::post('admin/add-service', [ServiceController::class, 'store'])->name('admin.addService.store');
    Route::get('admin/edit-service/{id}', [ServiceController::class, 'edit'])->name('admin.editService');
    Route::post('admin/edit-service/{id}', [ServiceController::class, 'update'])->name('admin.editService.update');
    Route::delete('admin/delete-service/{id}', [ServiceController::class, 'destroy'])->name('admin.deleteService');
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
