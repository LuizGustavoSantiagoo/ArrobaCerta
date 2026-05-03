<?php

use App\Controllers\HomeController;
use App\Controllers\DashBoardController;
use App\Controllers\AuthenticationsController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])->name('root');
Route::post('/authenticate', [AuthenticationsController::class, 'authenticate'])->name('authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthenticationsController::class, 'logout'])->name('logout');
});
