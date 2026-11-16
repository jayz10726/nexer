<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProxyController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VirtualNumberController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('.settings');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('.settings.update');

    Route::get('/plans', [PlanController::class, 'index'])->name('.plans');
    Route::get('/plans/create', [PlanController::class, 'create'])->name('.plans.create');
    Route::post('/plans', [PlanController::class, 'store'])->name('.plans.store');
    Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('.plans.edit');
    Route::patch('/plans/{plan}', [PlanController::class, 'update'])->name('.plans.update');
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('.plans.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('.users');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('.users.show');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('.users.update');
    Route::post('/users/{user}/wallet-adjust', [UserController::class, 'adjustWallet'])->name('.users.wallet-adjust');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('super_admin')
        ->name('.users.destroy');

    Route::get('/proxies', [ProxyController::class, 'index'])->name('.proxies');
    Route::get('/proxies/create', [ProxyController::class, 'create'])->name('.proxies.create');
    Route::post('/proxies', [ProxyController::class, 'store'])->name('.proxies.store');
    Route::get('/proxies/{proxy}/edit', [ProxyController::class, 'edit'])->name('.proxies.edit');
    Route::patch('/proxies/{proxy}', [ProxyController::class, 'update'])->name('.proxies.update');
    Route::delete('/proxies/{proxy}', [ProxyController::class, 'destroy'])->name('.proxies.destroy');

    Route::get('/virtual-numbers', [VirtualNumberController::class, 'index'])->name('.numbers');
    Route::get('/virtual-numbers/create', [VirtualNumberController::class, 'create'])->name('.numbers.create');
    Route::post('/virtual-numbers', [VirtualNumberController::class, 'store'])->name('.numbers.store');
    Route::get('/virtual-numbers/{number}/edit', [VirtualNumberController::class, 'edit'])->name('.numbers.edit');
    Route::patch('/virtual-numbers/{number}', [VirtualNumberController::class, 'update'])->name('.numbers.update');
    Route::delete('/virtual-numbers/{number}', [VirtualNumberController::class, 'destroy'])->name('.numbers.destroy');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('.transactions');
});
