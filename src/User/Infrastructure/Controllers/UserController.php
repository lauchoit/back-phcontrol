<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Lauchoit\LaravelHexMod\User\Application\UseCases\CreateUserUseCase;
use Lauchoit\LaravelHexMod\User\Application\UseCases\DeleteByIdUserUseCase;
use Lauchoit\LaravelHexMod\User\Application\UseCases\FindAllUserUseCase;
use Lauchoit\LaravelHexMod\User\Application\UseCases\FindByIdUserUseCase;
use Lauchoit\LaravelHexMod\User\Application\UseCases\SyncRolesToUserUseCase;
use Lauchoit\LaravelHexMod\User\Application\UseCases\UpdateByIdUserUseCase;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Entity\UserSource;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\FiltersNotFoundException;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Requests\CreateUserRequest;
use Lauchoit\LaravelHexMod\User\Infrastructure\Requests\SyncRolesToUserRequest;
use Lauchoit\LaravelHexMod\User\Infrastructure\Requests\UpdateUserRequest;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly FindAllUserUseCase $findAllUserUseCase,
        private readonly FindByIdUserUseCase $findByIdUserUseCase,
        private readonly DeleteByIdUserUseCase $deleteByIdUserUseCase,
        private readonly UpdateByIdUserUseCase $updateByIdUserUseCase,
        private readonly SyncRolesToUserUseCase $syncRolesToUserUseCase,
    ) {}

    public function create(CreateUserRequest $user): JsonResponse
    {
        try {
            $newUser = $this->createUserUseCase->execute($user->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_CREATED, data: UserResource::make($newUser), code: 201);
        } catch (\Exception $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 500);
        }
    }

    private function validateFilters(array $filters): array
    {
        //        $validFilters = ['name', 'lastname', 'phone', 'email'];
        $validFilters = array_filter(UserSource::FIELDS, fn ($field) => $field !== 'language');

        foreach ($filters as $key => $value) {
            if ($value === null || $value === '' || (is_array($value) && count($value) === 0)) {
                unset($filters[$key]);
            }
        }
        $invalidFilters = array_diff(array_keys($filters), $validFilters);
        if (! empty($invalidFilters)) {
            throw new FiltersNotFoundException($validFilters);
        }

        return array_intersect_key($filters, array_flip($validFilters));
    }

    public function findAll(): JsonResponse
    {
        $this->authorize('findAll', UserModel::class);

        try {
            $filters = $this->validateFilters(request()->all());
            $users = $this->findAllUserUseCase->execute(filters: $filters);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: UserResource::collection($users));
        } catch (\Exception $e) {
            if ($e instanceof FiltersNotFoundException) {

                return ApiResponse::error(message: $e->getMessage(), code: 400);
            }

            return ApiResponse::error(message: $e->getMessage(), code: 500);
        }
    }

    /**
     * Find a User entity by its ID.
     *
     * @param  string  $userId
     */
    public function findById($userId): JsonResponse
    {
        $this->authorize('findById', [UserModel::class, $userId]);
        try {
            $user = $this->findByIdUserUseCase->execute($userId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: UserResource::make($user));
        } catch (UserNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }

    }

    /**
     * Delete a User entity by its ID.
     *
     * @param  string  $userId
     */
    public function deleteById($userId): JsonResponse
    {
        $this->authorize('deleteById', [UserModel::class]);
        try {
            $deleted = $this->deleteByIdUserUseCase->execute($userId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $deleted);
        } catch (UserNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Update a User entity by its ID.
     *
     * @param  string  $userId
     */
    public function updateById($userId, UpdateUserRequest $data): JsonResponse
    {
        $this->authorize('updateById', [UserModel::class, $userId]);
        try {
            $userUpdated = $this->updateByIdUserUseCase->execute($userId, $data->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: UserResource::make($userUpdated));
        } catch (UserNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    public function syncRolesToUser(SyncRolesToUserRequest $request): JsonResponse
    {
        $this->authorize('syncRolesToUser', UserModel::class);
        try {
            ['userId' => $userId, 'roleIds' => $roleIds] = $request->validated();
            $userUpdated = $this->syncRolesToUserUseCase->execute($userId, $roleIds);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: $userUpdated);
        } catch (UserNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }
}
