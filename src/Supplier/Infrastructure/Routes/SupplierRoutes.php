<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Controllers\SupplierController;

Route::prefix('api/supplier')->middleware('auth:api')->group(function () {
    Route::post('/', [SupplierController::class, 'create'])->name('supplier.create');
    Route::get('/', [SupplierController::class, 'findAll'])->name('supplier.findAll');
    Route::get('/{supplierId}', [SupplierController::class, 'findById'])->name('supplier.findById');
    Route::delete('/{supplierId}', [SupplierController::class, 'deleteById'])->name('supplier.deleteById');
    Route::patch('/{supplierId}', [SupplierController::class, 'updateById'])->name('supplier.updateById');
})->middleware('api');
