<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class AddUserToPermissionUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    public function execute(array $data): bool
    {
        $userId = $data['userId'];
        $permissionIds = $data['permissionIds'];

        return $this->permissionRepository->addUserToPermission($userId, $permissionIds);
    }
}
