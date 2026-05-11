<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\AddRoleToPermissionUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\AddUserToPermissionUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\CreatePermissionUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\DeleteByIdPermissionUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\FindAllPermissionUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\FindAuthenticatedUserPermissionsUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\FindByIdPermissionUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\RevokePermissionFromUserUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\UpdateByIdPermissionUseCaseImpl;

class PermissionRepositoryImpl extends PermissionRepository
{
    public function __construct(
        private readonly CreatePermissionUseCaseImpl $create,
        private readonly FindAllPermissionUseCaseImpl $findAll,
        private readonly FindAuthenticatedUserPermissionsUseCaseImpl $findAuthenticatedUserPermissions,
        private readonly FindByIdPermissionUseCaseImpl $findById,
        private readonly DeleteByIdPermissionUseCaseImpl $deleteById,
        private readonly UpdateByIdPermissionUseCaseImpl $updateById,
        private readonly AddRoleToPermissionUseCaseImpl $addRoleToPermission,
        private readonly AddUserToPermissionUseCaseImpl $addUserToPermission,
        private readonly RevokePermissionFromUserUseCaseImpl $revokePermissionFomUser,
    ) {}

    /**
     * Create a new Permission entity.
     */
    public function create(array $newPermission): Permission
    {
        return $this->create->execute($newPermission);
    }

    /**
     * Finds all Permission entities.
     *
     * @return Permission[]
     */
    public function findAll(): array
    {
        return $this->findAll->execute();
    }

    /**
     * Finds all effective Permission entities assigned to a user.
     *
     * @param  string  $userId
     * @return Permission[]
     */
    public function findAuthenticatedUserPermissions($userId): array
    {
        return $this->findAuthenticatedUserPermissions->execute($userId);
    }

    /**
     * Finds a Permission by its ID.
     *
     * @param  string  $permissionId
     */
    public function findById($permissionId): ?Permission
    {
        return $this->findById->execute($permissionId);
    }

    /**
     * Deletes a Permission by its ID.
     */
    public function deleteById(Permission $permission): bool
    {
        return $this->deleteById->execute($permission);
    }

    /**
     * Update a Permission entity by its ID.
     *
     * @param  string  $permissionId
     */
    public function updateById($permissionId, array $data): Permission
    {
        return $this->updateById->execute($permissionId, $data);
    }

    /**
     * @param  string  $roleId
     */
    public function addRoleToPermission($roleId, array $data): bool
    {
        return $this->addRoleToPermission->execute($roleId, $data);
    }

    /**
     * @param  string  $userId
     */
    public function addUserToPermission($userId, array $data): bool
    {
        return $this->addUserToPermission->execute($userId, $data);
    }

    public function revokePermissionFromUser($userId, array $data): bool
    {
        return $this->revokePermissionFomUser->execute($userId, $data);
    }
}
