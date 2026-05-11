<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Controllers\TemplateNotificationController;

Route::prefix('api/template-notification')->group(function () {
    Route::post('/', [TemplateNotificationController::class, 'create'])
        ->middleware('auth:api')
        ->name('mail-template.create');
    Route::get('/', [TemplateNotificationController::class, 'findAll'])
        ->middleware('auth:api')
        ->name('mail-template.findAll');
    Route::get('/{mailTemplateId}', [TemplateNotificationController::class, 'findById'])
        ->middleware('auth:api')
        ->name('mail-template.findById');
    Route::delete('/{mailTemplateId}', [TemplateNotificationController::class, 'deleteById'])
        ->middleware('auth:api')
        ->name('mail-template.deleteById');
    Route::patch('/{mailTemplateId}', [TemplateNotificationController::class, 'updateById'])
        ->middleware('auth:api')
        ->name('mail-template.updateById');
    Route::get('/{key}/{language}', [TemplateNotificationController::class, 'findByKey'])
        ->middleware('auth:api')
        ->name('mail-template.findByKey');
})->middleware('api');
