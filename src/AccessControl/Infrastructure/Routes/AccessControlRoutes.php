<?php

use Illuminate\Support\Facades\Route;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Controllers\PermissionController;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Controllers\RoleController;

Route::prefix('api/access-control/permission')->group(function () {
    Route::post('/', [PermissionController::class, 'create'])->name('permission.create')
        ->middleware('auth:api');
    Route::get('/', [PermissionController::class, 'findAll'])->name('permission.findAll')
        ->middleware('auth:api');
    Route::get('/authenticated-user', [PermissionController::class, 'findAuthenticatedUserPermissions'])->name('permission.authenticatedUser')
        ->middleware('auth:api');
    Route::get('/{permissionId}', [PermissionController::class, 'findById'])->name('permission.findById')
        ->middleware('auth:api');
    Route::delete('/{permissionId}', [PermissionController::class, 'deleteById'])->name('permission.deleteById')
        ->middleware('auth:api');
    Route::patch('/sync-permissions-to-role', [PermissionController::class, 'syncPermissionsToRole'])->name('permissions.to.role')
        ->middleware('auth:api');
    Route::patch('/{permissionId}', [PermissionController::class, 'updateById'])->name('permission.updateById')
        ->middleware('auth:api');
    Route::post('/add-permissions-to-user', [PermissionController::class, 'addPermissionsToUser'])->name('permission.add.to.user')
        ->middleware('auth:api');
    Route::post('/revoke-permissions-to-user', [PermissionController::class, 'revokePermissionsToUser'])->name('permission.revoke.to.user')
        ->middleware('auth:api');
})->middleware('api');

Route::prefix('api/access-control/role')->group(function () {
    Route::post('/', [RoleController::class, 'create'])->name('role.create')
        ->middleware('auth:api');
    Route::get('/', [RoleController::class, 'findAll'])->name('role.findAll')
        ->middleware('auth:api');
    Route::get('/user/{userId}', [RoleController::class, 'findByUserId'])->name('role.findByUserId')
        ->middleware('auth:api');
    Route::get('/{roleId}', [RoleController::class, 'findById'])->name('role.findById')
        ->middleware('auth:api');
    Route::delete('/{roleId}', [RoleController::class, 'deleteById'])->name('role.deleteById')
        ->middleware('auth:api');
    Route::patch('/{roleId}', [RoleController::class, 'updateById'])->name('role.updateById')
        ->middleware('auth:api');
})->middleware('api');
