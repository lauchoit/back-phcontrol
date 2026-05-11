<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\AddUserToPermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\CreatePermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\DeleteByIdPermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindAllPermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindAuthenticatedUserPermissionsUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindByIdPermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\RevokePermissionFromUserUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\SyncRoleToPermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\UpdateByIdPermissionUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\PermissionNotFoundException;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\AddRolePermissionRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\AddUserPermissionRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\CreatePermissionRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\RevokePermissionFromUserRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\UpdatePermissionRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\PermissionResource;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;

class PermissionController extends Controller
{
    public function __construct(
        private readonly CreatePermissionUseCase $createPermissionUseCase,
        private readonly FindAllPermissionUseCase $findAllPermissionUseCase,
        private readonly FindAuthenticatedUserPermissionsUseCase $findAuthenticatedUserPermissionsUseCase,
        private readonly FindByIdPermissionUseCase $findByIdPermissionUseCase,
        private readonly DeleteByIdPermissionUseCase $deleteByIdPermissionUseCase,
        private readonly UpdateByIdPermissionUseCase $updateByIdPermissionUseCase,
        private readonly SyncRoleToPermissionUseCase $syncRoleToPermissionUseCase,
        private readonly AddUserToPermissionUseCase $addUserToPermissionUseCase,
        private readonly RevokePermissionFromUserUseCase $revokePermissionFromUserUseCase,
    ) {}

    public function create(CreatePermissionRequest $permission): JsonResponse
    {
        $this->authorize('create', [PermissionModel::class]);
        $newPermission = $this->createPermissionUseCase->execute($permission->validated());

        return ApiResponse::success(message: ApiResponse::$SUCCESS_CREATED, data: PermissionResource::make($newPermission), code: 201);
    }

    public function findAll(): JsonResponse
    {
        $this->authorize('findAll', [PermissionModel::class]);
        $permissions = $this->findAllPermissionUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: PermissionResource::collection($permissions));
    }

    public function findAuthenticatedUserPermissions(): JsonResponse
    {
        $this->authorize('findAuthenticatedUserPermissions', [PermissionModel::class]);

        try {
            $permissions = $this->findAuthenticatedUserPermissionsUseCase->execute(auth()->id());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: PermissionResource::collection($permissions));
        } catch (UserNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Find a Permission entity by its ID.
     *
     * @param  string  $permissionId
     */
    public function findById($permissionId): JsonResponse
    {
        $this->authorize('findById', [PermissionModel::class]);
        $permission = $this->findByIdPermissionUseCase->execute($permissionId);
        if (! $permission) {
            return ApiResponse::error(message: ApiResponse::$ERROR_NOT_FOUND, code: 404);
        }

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: PermissionResource::make($permission));
    }

    /**
     * Delete a Permission entity by its ID.
     *
     * @param  string  $permissionId
     */
    public function deleteById($permissionId): JsonResponse
    {
        $this->authorize('deleteById');
        try {
            $deleted = $this->deleteByIdPermissionUseCase->execute($permissionId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $deleted);
        } catch (PermissionNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Update a Permission entity by its ID.
     *
     * @param  string  $permissionId
     */
    public function updateById($permissionId, UpdatePermissionRequest $data): JsonResponse
    {
        $this->authorize('updateById', PermissionModel::class);
        try {
            $permissionUpdated = $this->updateByIdPermissionUseCase->execute($permissionId, $data->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: PermissionResource::make($permissionUpdated));
        } catch (PermissionNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    public function syncPermissionsToRole(AddRolePermissionRequest $request): JsonResponse
    {
        // TODO: Mover este metodo a RoleController
        $this->authorize('roleSyncPermission', RoleModel::class);
        try {
            $rolesSync = $this->syncRoleToPermissionUseCase->execute($request->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_ADDED, data: $rolesSync, code: 201);
        } catch (\RuntimeException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: $e->getCode());
        }
    }

    public function addPermissionsToUser(AddUserPermissionRequest $request): JsonResponse
    {
        $this->authorize('addPermissionToRole', PermissionModel::class);
        try {
            $rolesSync = $this->addUserToPermissionUseCase->execute($request->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_ADDED, data: $rolesSync, code: 201);
        } catch (\RuntimeException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: $e->getCode());
        }
    }

    public function revokePermissionsToUser(RevokePermissionFromUserRequest $data): JsonResponse
    {
        $this->authorize('deleteById', PermissionModel::class);
        $response = $this->revokePermissionFromUserUseCase->execute($data->validated());

        return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $response);
    }
}
