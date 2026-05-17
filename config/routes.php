<?php

use App\Controllers\HomeController;
use App\Controllers\DashBoardController;
use App\Controllers\AuthenticationsController;
use App\Controllers\CattleController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])->name('root');
Route::post('/authenticate', [AuthenticationsController::class, 'authenticate'])->name('authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthenticationsController::class, 'logout'])->name('logout');

    // Cattle routes
    Route::get('/cattle', [CattleController::class, 'index'])->name('cattle.index');
    Route::post('/cattle/store', [CattleController::class, 'store'])->name('cattle.store');
    Route::delete('/cattle/destroy/{cow_id}', [CattleController::class, 'destroy'])->name('cattle.destroy');
    Route::get('/cattle/edit/{cow_id}', [CattleController::class, 'edit'])->name('cattle.edit');
    Route::post('/cattle/update/{cow_id}', [CattleController::class, 'update'])->name('cattle.update');
});
