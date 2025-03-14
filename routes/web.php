<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\PositionController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('users')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('create', [UserController::class, 'create'])->name('create');
});

Route::prefix('token')->group(function () {
    Route::get('/', [TokenController::class, 'index']);
});

Route::prefix('positions')->group(function () {
    Route::get('', [PositionController::class, 'index']);
});

