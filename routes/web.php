<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/create_bookings', [BookingController::class, 'index'])->name('bookings.index');

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
