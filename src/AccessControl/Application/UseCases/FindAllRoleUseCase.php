<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;

readonly class FindAllRoleUseCase
{
    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    /**
     * Finds all Role entities.
     *
     * @return Role[]
     */
    public function execute(): array
    {
        return $this->roleRepository->findAll();
    }
}
