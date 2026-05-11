<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\CreateRoleUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\DeleteByIdRoleUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\FindAllRoleUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\FindByIdRoleUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\FindByUserIdRoleUseCaseImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases\UpdateByIdRoleUseCaseImpl;

class RoleRepositoryImpl extends RoleRepository
{
    public function __construct(
        private readonly CreateRoleUseCaseImpl $create,
        private readonly FindAllRoleUseCaseImpl $findAll,
        private readonly FindByIdRoleUseCaseImpl $findById,
        private readonly FindByUserIdRoleUseCaseImpl $findByUserId,
        private readonly DeleteByIdRoleUseCaseImpl $deleteById,
        private readonly UpdateByIdRoleUseCaseImpl $updateById,
    ) {}

    /**
     * Create a new Role entity.
     */
    public function create(array $newRole): Role
    {
        return $this->create->execute($newRole);
    }

    /**
     * Finds all Role entities.
     *
     * @return Role[]
     */
    public function findAll(): array
    {
        return $this->findAll->execute();
    }

    /**
     * Finds a Role by its ID.
     *
     * @param  string  $roleId
     */
    public function findById($roleId): ?Role
    {
        return $this->findById->execute($roleId);
    }

    /**
     * Finds all Role entities by user ID.
     *
     * @param  string  $userId
     * @return Role[]
     */
    public function findByUserId($userId): array
    {
        return $this->findByUserId->execute($userId);
    }

    /**
     * Deletes a Role by its ID.
     */
    public function deleteById(Role $role): bool
    {
        return $this->deleteById->execute($role);
    }

    /**
     * Update a Role entity by its ID.
     *
     * @param  string  $roleId
     */
    public function updateById($roleId, array $data): Role
    {
        return $this->updateById->execute($roleId, $data);
    }
}
