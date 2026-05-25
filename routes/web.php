<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BiosecurityAuditController;
use App\Http\Controllers\HealthAlertController;
use App\Http\Controllers\VisitorsLogController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Root route redirects to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes (Full-Featured)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Grouped Biosecurity Routes - Protected by standard Laravel 'auth' middleware
Route::middleware(['auth'])->group(function () {
    
    // Dynamic Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Biosecurity Audits
    Route::get('/audits', [BiosecurityAuditController::class, 'index'])->name('audits.index');
    Route::get('/audits/create', [BiosecurityAuditController::class, 'create'])->name('audits.create');
    Route::post('/audits', [BiosecurityAuditController::class, 'store'])->name('audits.store');

    // Health, Mortality & Outbreak Alerts
    Route::get('/alerts', [HealthAlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [HealthAlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [HealthAlertController::class, 'store'])->name('alerts.store');
    Route::post('/alerts/{id}/resolve', [HealthAlertController::class, 'resolve'])->name('alerts.resolve');

    // Visitor & Vehicle Logs
    Route::get('/visitors', [VisitorsLogController::class, 'index'])->name('visitors.index');
    Route::get('/visitors/checkin', [VisitorsLogController::class, 'create'])->name('visitors.checkin');
    Route::post('/visitors', [VisitorsLogController::class, 'store'])->name('visitors.store');
    Route::post('/visitors/{id}/checkout', [VisitorsLogController::class, 'checkout'])->name('visitors.checkout');
    
    // User Session Termination
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
