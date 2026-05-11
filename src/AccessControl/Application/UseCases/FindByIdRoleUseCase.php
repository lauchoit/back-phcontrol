<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;

readonly class FindByIdRoleUseCase
{
    public function __construct(
        private readonly RoleRepository $roleRepository
    ) {}

    /**
     * Find by ID Role entities.
     *
     * @param  string  $roleId
     */
    public function execute($roleId): ?Role
    {
        return $this->roleRepository->findById($roleId);
    }
}
