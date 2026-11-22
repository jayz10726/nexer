<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login');

    Route::get('super-admin', [AuthenticatedSessionController::class, 'createSuperAdmin'])
        ->name('super-admin.login');

    Route::post('super-admin', [AuthenticatedSessionController::class, 'storeSuperAdmin'])
        ->middleware('throttle:login')
        ->name('super-admin.authenticate');

    Route::get('auth/google', [GoogleController::class, 'redirect'])
        ->name('auth.google');
});

Route::get('auth/google/callback', [GoogleController::class, 'callback'])
    ->name('auth.google.callback');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('auth/google/link', [GoogleController::class, 'beginLink'])
        ->name('auth.google.link');
});
