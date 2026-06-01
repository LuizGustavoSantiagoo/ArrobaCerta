<?php

use App\Controllers\HomeController;
use App\Controllers\DashBoardController;
use App\Controllers\AuthenticationsController;
use App\Controllers\CattleController;
use App\Controllers\CattleImagesController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])->name('root');
Route::post('/authenticate', [AuthenticationsController::class, 'authenticate'])->name('authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthenticationsController::class, 'logout'])->name('logout');

    // Cattle routes
    Route::get('/cattle', [CattleController::class, 'index'])->name('cattle.index');
    Route::post('/cattle', [CattleController::class, 'create'])->name('cattle.create');
    Route::delete('/cattle/{id}', [CattleController::class, 'destroy'])->name('cattle.destroy');
    Route::get('/cattle/{id}/edit', [CattleController::class, 'edit'])->name('cattle.edit');
    Route::put('/cattle/{id}', [CattleController::class, 'update'])->name('cattle.update');

    // cattle images routes
    Route::post('/cattleimages', [CattleImagesController::class, 'create'])->name('cattleimages.create');
    Route::put('/cattleimages/{id}', [CattleImagesController::class, 'update'])->name('cattleimages.update');
    Route::delete('/cattleimages/{id}', [CattleImagesController::class, 'destroy'])->name('cattleimages.destroy');
});
