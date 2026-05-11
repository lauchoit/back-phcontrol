<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class FindAuthenticatedUserPermissionsUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * Finds all effective permissions assigned to a user.
     *
     * @param  string  $userId
     * @return Permission[]
     */
    public function execute($userId): array
    {
        return $this->permissionRepository->findAuthenticatedUserPermissions($userId);
    }
}
