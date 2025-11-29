<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        // Users management
        Route::resource('users', UserController::class);

        // Clubs management
        Route::resource('clubs', ClubController::class);

        // All events management
        Route::resource('events', EventController::class);

        // All registrations view
        Route::resource('registrations', RegistrationController::class);

        // All attendance logs
        Route::resource('attendance', AttendanceController::class);
    });

    // Officer routes (club-specific)
    Route::middleware('role:officer')->group(function () {
        // Officers can create events for their clubs
        Route::get('events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('events', [EventController::class, 'store'])->name('events.store');

        // Officers can manage their own events
        Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

        // Officers can view registrations for their events
        Route::get('registrations', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');

        // Officers can mark attendance for their events
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    });

    // Student routes
    Route::middleware('role:student')->group(function () {
        // Students can view events
        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');

        // Students can manage their registrations
        Route::get('registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
        Route::post('registrations', [RegistrationController::class, 'store'])->name('registrations.store');
        Route::get('registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');

        // Students can view their attendance history
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    });
});
