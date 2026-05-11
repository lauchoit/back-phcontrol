<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Controllers\ProductController;

Route::prefix('api/product')->middleware('auth:api')->group(function () {
    Route::post('/', [ProductController::class, 'create'])->name('product.create');
    Route::get('/', [ProductController::class, 'findAll'])->name('product.findAll');
    Route::get('/{productId}', [ProductController::class, 'findById'])->name('product.findById');
    Route::delete('/{productId}', [ProductController::class, 'deleteById'])->name('product.deleteById');
    Route::patch('/{productId}', [ProductController::class, 'updateById'])->name('product.updateById');
})->middleware('api');
