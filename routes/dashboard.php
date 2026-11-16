<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProxyController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\VirtualNumberController;
use App\Http\Controllers\Dashboard\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('dashboard')->name('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('');

    Route::get('/proxies', [ProxyController::class, 'index'])->name('.proxies');
    Route::get('/proxies/buy', [ProxyController::class, 'browse'])->name('.proxies.buy');
    Route::get('/proxies/buy/price', [ProxyController::class, 'price'])->name('.proxies.price');
    Route::post('/proxies/buy', [ProxyController::class, 'store'])->name('.proxies.store');

    Route::get('/virtual-numbers', [VirtualNumberController::class, 'index'])->name('.numbers');
    Route::get('/virtual-numbers/buy', [VirtualNumberController::class, 'browse'])->name('.numbers.buy');
    Route::get('/virtual-numbers/buy/price', [VirtualNumberController::class, 'price'])->name('.numbers.price');
    Route::post('/virtual-numbers/buy', [VirtualNumberController::class, 'store'])->name('.numbers.store');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('.transactions');

    Route::get('/wallet', [WalletController::class, 'index'])->name('.wallet');
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])->name('.wallet.topup');
    Route::get('/wallet/paystack/callback', [WalletController::class, 'callback'])->name('.wallet.callback');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('.settings');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('.settings.update');
});
