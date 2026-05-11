<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;

readonly class CreateRoleUseCase
{
    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    /**
     * Create a new Role entity.
     */
    public function execute(array $newRole): Role
    {
        return $this->roleRepository->create($newRole);
    }
}
