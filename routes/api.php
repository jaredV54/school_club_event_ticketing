<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistrationApiController;
use App\Http\Controllers\Api\UserApiController;

// API routes with web middleware for session handling
Route::middleware('web')->group(function () {
    Route::get('/registrations/search', [RegistrationApiController::class, 'search']);
    Route::get('/registrations/suggestions', [RegistrationApiController::class, 'suggestions']);

    Route::get('/users/search', [UserApiController::class, 'search']);
    Route::get('/users/suggestions', [UserApiController::class, 'suggestions']);
});
