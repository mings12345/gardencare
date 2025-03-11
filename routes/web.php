<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/bookings', [BookingController::class, 'index']);
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
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
