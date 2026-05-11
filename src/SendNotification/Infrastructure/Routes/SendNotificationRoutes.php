<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Controller\SendNotificationController;

Route::prefix('api/send-notification')->group(function () {
    Route::get('/', [SendNotificationController::class, 'findAll']);
})->middleware('api');
