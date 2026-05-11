<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class FindAllPermissionUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * Finds all Permission entities.
     *
     * @return Permission[]
     */
    public function execute(): array
    {
        return $this->permissionRepository->findAll();
    }
}
