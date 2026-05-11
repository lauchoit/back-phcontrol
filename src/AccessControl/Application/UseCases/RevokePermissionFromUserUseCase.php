<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class RevokePermissionFromUserUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    public function execute(array $data): bool
    {
        $userId = $data['userId'];
        $permissionIds = $data['permissionIds'];

        return $this->permissionRepository->revokePermissionFromUser($userId, $permissionIds);
    }
}
