<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/update-app', function () {
    Artisan::call('update-app');
    return "Successfully Updated";
});

Route::get('/reset-app', function () {
    Artisan::call('migrate:fresh --seed');
    return "Successfully Reseted";
});

require __DIR__.'/auth.php';
