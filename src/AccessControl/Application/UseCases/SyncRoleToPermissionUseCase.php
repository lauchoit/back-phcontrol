<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class SyncRoleToPermissionUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    public function execute(array $data): bool
    {
        $roleId = $data['roleId'];
        $permissionIds = $data['permissionIds'] ?? [];

        return $this->permissionRepository->addRoleToPermission($roleId, $permissionIds);
    }
}
