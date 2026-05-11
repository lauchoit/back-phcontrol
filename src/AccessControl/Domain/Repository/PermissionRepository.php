<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Repository;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;

abstract class PermissionRepository
{
    /**
     * Creates a new Permission entity.
     */
    abstract public function create(array $newPermission): Permission;

    /**
     * Finds all Permission entities.
     *
     * @return Permission[]
     */
    abstract public function findAll(): array;

    /**
     * Finds all effective Permission entities assigned to a user.
     *
     * @param  string  $userId
     * @return Permission[]
     */
    abstract public function findAuthenticatedUserPermissions($userId): array;

    /**
     * Finds a Permission by its ID.
     *
     * @param  string  $permissionId
     */
    abstract public function findById($permissionId): ?Permission;

    /**
     * Deletes a Permission by its ID.
     */
    abstract public function deleteById(Permission $permission): bool;

    /**
     * Update a Permission entity by its ID.
     *
     * @param  string  $permissionId
     */
    abstract public function updateById($permissionId, array $data): Permission;

    /**
     * @param  string  $roleId
     */
    abstract public function addRoleToPermission($roleId, array $data): bool;

    /**
     * @param  string  $userId
     */
    abstract public function addUserToPermission($userId, array $data): bool;

    /**
     * @param  string  $userId
     */
    abstract public function revokePermissionFromUser($userId, array $data): bool;
}
