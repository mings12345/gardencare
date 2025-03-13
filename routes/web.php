<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;


Route::get('/bookings', [BookingController::class, 'index']);
Route::get('admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    // Add more admin routes here
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
