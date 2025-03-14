<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PositionController;
use App\Http\Controllers\API\TokenController;

Route::prefix('v1')->group(function () {
    Route::prefix('users')->name('user.')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::get('{id}', [UserController::class, 'show']);

        Route::post('', [UserController::class, 'store'])
            ->name('store')->middleware('check.user.registration.token');
    });

    Route::get('/positions', [PositionController::class, 'index']);

    Route::get('/token', TokenController::class)->name('token');
});

