<?php

use App\Http\Controllers\Products\CreateController;
use App\Http\Controllers\Products\DeleteController;
use App\Http\Controllers\Products\IndexController;
use App\Http\Controllers\Products\ShowController;
use App\Http\Controllers\Products\UpdateController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', IndexController::class)->name('index');
        Route::post('/', CreateController::class)->name('create');
        Route::get('{product}', ShowController::class)->name('show');
        Route::put('{product}', UpdateController::class)->name('update');
        Route::delete('{product}', DeleteController::class)->name('delete');
    });
});

require __DIR__.'/settings.php';
