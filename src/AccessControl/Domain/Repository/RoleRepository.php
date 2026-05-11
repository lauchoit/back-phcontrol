<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Repository;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;

abstract class RoleRepository
{
    /**
     * Creates a new Role entity.
     */
    abstract public function create(array $newRole): Role;

    /**
     * Finds all Role entities.
     *
     * @return Role[]
     */
    abstract public function findAll(): array;

    /**
     * Finds a Role by its ID.
     *
     * @param  string  $roleId
     */
    abstract public function findById($roleId): ?Role;

    /**
     * Finds all Role entities by user ID.
     *
     * @param  string  $userId
     * @return Role[]
     */
    abstract public function findByUserId($userId): array;

    /**
     * Deletes a Role by its ID.
     */
    abstract public function deleteById(Role $role): bool;

    /**
     * Update a Role entity by its ID.
     *
     * @param  string  $roleId
     */
    abstract public function updateById($roleId, array $data): Role;
}
