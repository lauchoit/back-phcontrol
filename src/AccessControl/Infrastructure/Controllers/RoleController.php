<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\CreateRoleUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\DeleteByIdRoleUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindAllRoleUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindByIdRoleUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindByUserIdRoleUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\UpdateByIdRoleUseCase;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\RoleNotFoundException;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\CreateRoleRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests\UpdateRoleRequest;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources\RoleResource;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;

class RoleController extends Controller
{
    public function __construct(
        private readonly CreateRoleUseCase $createRoleUseCase,
        private readonly FindAllRoleUseCase $findAllRoleUseCase,
        private readonly FindByIdRoleUseCase $findByIdRoleUseCase,
        private readonly FindByUserIdRoleUseCase $findByUserIdRoleUseCase,
        private readonly DeleteByIdRoleUseCase $deleteByIdRoleUseCase,
        private readonly UpdateByIdRoleUseCase $updateByIdRoleUseCase,
    ) {}

    public function create(CreateRoleRequest $role): JsonResponse
    {
        $this->authorize('create');
        $newRole = $this->createRoleUseCase->execute($role->validated());

        return ApiResponse::success(message: ApiResponse::$SUCCESS_CREATED, data: RoleResource::make($newRole), code: 201);
    }

    public function findAll(): JsonResponse
    {
        $this->authorize('findAll', [RoleModel::class]);
        $roles = $this->findAllRoleUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: RoleResource::collection($roles));
    }

    /**
     * Find a Role entity by its ID.
     *
     * @param  string  $roleId
     */
    public function findById($roleId): JsonResponse
    {
        $this->authorize('findById', [RoleModel::class, $roleId]);
        $role = $this->findByIdRoleUseCase->execute($roleId);
        if (! $role) {
            return ApiResponse::error(message: ApiResponse::$ERROR_NOT_FOUND, code: 404);
        }

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: RoleResource::make($role));
    }

    /**
     * Find Roles by user ID.
     *
     * @param  string  $userId
     */
    public function findByUserId($userId): JsonResponse
    {
        $this->authorize('findByUserId', RoleModel::class);
        try {
            $roles = $this->findByUserIdRoleUseCase->execute($userId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: RoleResource::collection($roles));
        } catch (UserNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Delete a Role entity by its ID.
     *
     * @param  string  $roleId
     */
    public function deleteById($roleId): JsonResponse
    {
        $this->authorize('deleteById');
        try {
            $deleted = $this->deleteByIdRoleUseCase->execute($roleId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $deleted);
        } catch (RoleNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Update a Role entity by its ID.
     *
     * @param  string  $roleId
     */
    public function updateById($roleId, UpdateRoleRequest $data): JsonResponse
    {
        $this->authorize('updateById', RoleModel::class);
        try {
            $roleUpdated = $this->updateByIdRoleUseCase->execute($roleId, $data->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: RoleResource::make($roleUpdated));
        } catch (RoleNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }
}
