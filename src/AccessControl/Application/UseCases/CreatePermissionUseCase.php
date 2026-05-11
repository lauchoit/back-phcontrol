<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class CreatePermissionUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * Create a new Permission entity.
     */
    public function execute(array $newPermission): Permission
    {
        return $this->permissionRepository->create($newPermission);
    }
}
