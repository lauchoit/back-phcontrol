<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\PermissionNotFoundException;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;

readonly class DeleteByIdPermissionUseCase
{
    public function __construct(
        private PermissionRepository $permissionRepository,
        private FindByIdPermissionUseCase $findByIdPermissionUseCase,
    ) {}

    /**
     * @param  string  $permissionId
     */
    public function execute($permissionId): bool
    {
        $permission = $this->findByIdPermissionUseCase->execute($permissionId);
        if (! $permission) {
            throw new PermissionNotFoundException($permissionId);
        }

        return $this->permissionRepository->deleteById($permission);
    }
}
