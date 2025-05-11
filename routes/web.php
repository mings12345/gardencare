<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GardenerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\HomeownerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRequestController;


Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

// Admin Authentication Routes
Route::redirect('/login','/admin/login');
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// Admin Protected Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Profile routes
    Route::get('/admin/profile', [AdminDashboardController::class, 'showProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
    
    // Admin logout route
    Route::post('/admin/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Bookings Management
    Route::get('/manage-bookings', [BookingController::class, 'index'])->name('admin.manageBookings');
    
    // Users Management
    Route::get('/manage-users', [AuthController::class, 'index'])->name('admin.manageUsers');
    
    // Service Requests
    Route::get('/manage-service-requests', [ServiceRequestController::class, 'index'])->name('admin.manageServiceRequests');
    
    // Homeowners Management
    Route::prefix('homeowners')->group(function() {
        Route::get('/', [HomeownerController::class, 'index'])->name('admin.manageHomeowners');
        Route::get('/create', [HomeownerController::class, 'create'])->name('admin.addHomeowner');
        Route::post('/', [HomeownerController::class, 'store'])->name('admin.storeHomeowner');
        Route::get('/{id}', [HomeownerController::class, 'show'])->name('admin.viewHomeowner');
        Route::get('/{id}/edit', [HomeownerController::class, 'edit'])->name('admin.editHomeowner');
        Route::put('/{id}', [HomeownerController::class, 'update'])->name('admin.updateHomeowner');
        Route::delete('/{id}', [HomeownerController::class, 'destroy'])->name('admin.deleteHomeowner');
    });
    
    // Gardeners Management
    Route::prefix('gardeners')->group(function() {
        Route::get('/', [GardenerController::class, 'index'])->name('admin.manageGardeners');
        Route::get('/create', [GardenerController::class, 'create'])->name('admin.addGardener');
        Route::post('/', [GardenerController::class, 'store'])->name('admin.storeGardener');
        Route::get('/{id}', [GardenerController::class, 'show'])->name('admin.viewGardener');
        Route::get('/{id}/edit', [GardenerController::class, 'edit'])->name('admin.editGardener');
        Route::put('/{id}', [GardenerController::class, 'update'])->name('admin.updateGardener');
        Route::delete('/{id}', [GardenerController::class, 'destroy'])->name('admin.deleteGardener');
    });
    
    // Service Providers Management
    Route::prefix('service-providers')->group(function() {
        Route::get('/', [ServiceProviderController::class, 'index'])->name('admin.manageServiceProviders');
        Route::get('/create', [ServiceProviderController::class, 'create'])->name('admin.addServiceProvider');
        Route::post('/', [ServiceProviderController::class, 'store'])->name('admin.storeServiceProvider');
        Route::get('/{id}', [ServiceProviderController::class, 'show'])->name('admin.viewServiceProvider');
        Route::get('/{id}/edit', [ServiceProviderController::class, 'edit'])->name('admin.editServiceProvider');
        Route::put('/{id}', [ServiceProviderController::class, 'update'])->name('admin.updateServiceProvider');
        Route::delete('/{id}', [ServiceProviderController::class, 'destroy'])->name('admin.deleteServiceProvider');
    });
    
    // Services Management
    Route::prefix('services')->group(function() {
        Route::get('/', [ServiceController::class, 'index'])->name('admin.manageServices');
        Route::get('/create', [ServiceController::class, 'create'])->name('admin.addService');
        Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
        Route::put('/{id}', [ServiceController::class, 'update'])->name('admin.updateService');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('admin.deleteService');
    });
    
    // Feedback Management
    Route::get('/admin/manageRatings', [AdminDashboardController::class, 'manageRatings'])
    ->name('admin.manageRatings');
    
    // Reports
    Route::get('/admin/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
    Route::post('/admin/export-reports', [AdminDashboardController::class, 'exportReports'])->name('admin.exportReports');
});

// System Routes
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;


Route::get('/update-app', function () {
    $artisanPath = base_path('artisan');
    $output = '';
    
    $commands = [
        ['php', $artisanPath, 'update-app'],
        ['php', $artisanPath, 'optimize']
    ];
    
    foreach ($commands as $command) {
        $process = new Process($command, base_path());
        $process->setTimeout(300); // 5 minute timeout
        
        try {
            $process->mustRun();
            $output .= "> " . implode(' ', $command) . "\n";
            $output .= $process->getOutput() . "\n\n";
        } catch (\Exception $e) {
            $output .= "ERROR: " . $e->getMessage() . "\n";
            $output .= $process->getErrorOutput() . "\n\n";
        }
    }
    
    return '<pre style="background:#f0f0f0; padding:20px; border-radius:5px; font-family:monospace;">'
           . htmlspecialchars($output)
           . '</pre>';
});

Route::get('/reset-app', function () {
    $artisanPath = base_path('artisan');
    $output = '';
    
    $commands = [
        ['php', $artisanPath, 'migrate:fresh', '--seed', '--force'],
        ['php', $artisanPath, 'optimize']
    ];
    
    foreach ($commands as $command) {
        $process = new Process($command, base_path());
        $process->setTimeout(300);
        
        try {
            $process->mustRun();
            $output .= ">Running Command \n";
            $output .= $process->getOutput() . "\n\n";
        } catch (\Exception $e) {
            $output .= "ERROR: " . $e->getMessage() . "\n";
            $output .= $process->getErrorOutput() . "\n\n";
        }
    }
    
    return '<pre style="background:#f0f0f0;padding:20px;">'.htmlspecialchars($output).'</pre>';
});
require __DIR__.'/auth.php';