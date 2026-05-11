<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Controllers\AuthController;

Route::prefix('api/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me')->middleware(['auth:api']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware(['auth:api']);
    Route::get('/forget-password/{data}', [AuthController::class, 'forgetPassword'])->name('auth.forget.password');
    Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('auth.reset.password');
})->middleware('api');
