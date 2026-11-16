<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/proxies', [PageController::class, 'proxies'])->name('proxies');
Route::get('/virtual-numbers', [PageController::class, 'virtualNumbers'])->name('virtual-numbers');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/admin.php';
