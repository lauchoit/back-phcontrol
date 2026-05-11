<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\User\Infrastructure\Controllers\UserController;

Route::prefix('api/user')->group(function () {
    Route::post('/', [UserController::class, 'create'])
        ->name('user.create');
    Route::get('/', [UserController::class, 'findAll'])
        ->name('user.findAll')
        ->middleware(['auth:api']);
    Route::get('/{userId}', [UserController::class, 'findById'])
        ->name('user.findById')
        ->middleware(['auth:api']);
    Route::delete('/{userId}', [UserController::class, 'deleteById'])
        ->name('user.deleteById')
        ->middleware(['auth:api']);
    Route::patch('/{userId}', [UserController::class, 'updateById'])
        ->middleware(['auth:api'])
        ->name('user.updateById');
    Route::put('sync-roles-to-user', [UserController::class, 'syncRolesToUser'])
        ->middleware(['auth:api'])
        ->name('user.sync-roles-to-user');

})->middleware('api');
