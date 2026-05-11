<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class UpdateByIdPermissionUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository,
    ) {}

    /**
     * Update a Permission entity by its ID.
     *
     * @param  string  $permissionId
     */
    public function execute($permissionId, array $data): Permission
    {
        return $this->permissionRepository->updateById($permissionId, $data);
    }
}
