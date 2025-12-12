<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApprovalController;

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

        // Event visibility toggle
        Route::post('events/{event}/toggle-hidden', [EventController::class, 'toggleHidden'])->name('events.toggle-hidden');
    });

    // Events - accessible by admin, officer, and student with different permissions
    Route::get('events', [EventController::class, 'index'])->name('events.index')->middleware('officer.club');
    Route::get('events/create', [EventController::class, 'create'])->name('events.create')->middleware(['role:admin,officer', 'officer.club']);
    Route::post('events', [EventController::class, 'store'])->name('events.store')->middleware(['role:admin,officer', 'officer.club']);
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show')->middleware('officer.club');
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware(['role:admin,officer', 'officer.club']);
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update')->middleware(['role:admin,officer', 'officer.club']);
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy')->middleware(['role:admin,officer', 'officer.club']);

    // Registrations - different access for different roles
    Route::get('registrations', [RegistrationController::class, 'index'])->name('registrations.index')->middleware('officer.club');
    Route::get('registrations/create', [RegistrationController::class, 'create'])->name('registrations.create')->middleware('role:admin,officer,student');
    Route::post('registrations', [RegistrationController::class, 'store'])->name('registrations.store')->middleware('role:admin,officer,student');
    Route::get('registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show')->middleware('officer.club');
    Route::get('registrations/{registration}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit')->middleware('role:admin,officer');
    Route::put('registrations/{registration}', [RegistrationController::class, 'update'])->name('registrations.update')->middleware('role:admin,officer');
    Route::delete('registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy')->middleware('role:admin,officer');

    // Attendance - accessible by all roles with different permissions
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index')->middleware('officer.club');
    Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create')->middleware(['role:admin,officer', 'officer.club']);
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware(['role:admin,officer', 'officer.club']);
    Route::get('attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show')->middleware('officer.club');
    Route::get('attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit')->middleware(['role:admin,officer', 'officer.club']);
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update')->middleware(['role:admin,officer', 'officer.club']);
    Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy')->middleware(['role:admin,officer', 'officer.club']);

    // Approvals - admin and officer access
    Route::get('approvals/event-registrations', [ApprovalController::class, 'eventRegistrationsIndex'])->name('approvals.event-registrations.index')->middleware('role:admin,officer');
    Route::post('approvals/event-registrations/{pendingRegistration}/approve', [ApprovalController::class, 'approveEventRegistration'])->name('approvals.event-registrations.approve')->middleware('role:admin,officer');
    Route::post('approvals/event-registrations/{pendingRegistration}/reject', [ApprovalController::class, 'rejectEventRegistration'])->name('approvals.event-registrations.reject')->middleware('role:admin,officer');

    // User account approvals - admin only
    Route::get('approvals/user-accounts', [ApprovalController::class, 'userAccountsIndex'])->name('approvals.user-accounts.index')->middleware('role:admin');
    Route::post('approvals/user-accounts/{pendingAccount}/approve', [ApprovalController::class, 'approveUserAccount'])->name('approvals.user-accounts.approve')->middleware('role:admin');
    Route::post('approvals/user-accounts/{pendingAccount}/reject', [ApprovalController::class, 'rejectUserAccount'])->name('approvals.user-accounts.reject')->middleware('role:admin');
});
