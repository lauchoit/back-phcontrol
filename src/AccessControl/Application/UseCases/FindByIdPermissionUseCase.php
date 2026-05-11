<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class FindByIdPermissionUseCase
{
    public function __construct(
        private readonly PermissionRepository $permissionRepository
    ) {}

    /**
     * Find by ID Permission entities.
     *
     * @param  string  $permissionId
     */
    public function execute($permissionId): ?Permission
    {
        return $this->permissionRepository->findById($permissionId);
    }
}
