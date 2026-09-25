<?php

use App\Http\Controllers\Notifications\ClearController;
use App\Http\Controllers\Notifications\UpdateController as NotificationUpdateController;
use App\Http\Controllers\Products\CreateController;
use App\Http\Controllers\Products\DeleteController;
use App\Http\Controllers\Products\IndexController;
use App\Http\Controllers\Products\ShowController;
use App\Http\Controllers\Products\UpdateController;
use App\Http\Controllers\StockMovements\CreateController as StockMovementCreateController;
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
        Route::post('{product}/movements', StockMovementCreateController::class)->name('movements.create');
    });

    Route::patch('notifications/{notification}', NotificationUpdateController::class)->name('notifications.update');
    Route::delete('notifications/read', ClearController::class)->name('notifications.clear');
});

require __DIR__.'/settings.php';
