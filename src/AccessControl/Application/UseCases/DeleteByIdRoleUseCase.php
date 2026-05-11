<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\RoleNotFoundException;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;

readonly class DeleteByIdRoleUseCase
{
    public function __construct(
        private RoleRepository $roleRepository,
        private FindByIdRoleUseCase $findByIdRoleUseCase,
    ) {}

    /**
     * @param  string  $roleId
     */
    public function execute($roleId): bool
    {
        $role = $this->findByIdRoleUseCase->execute($roleId);
        if (! $role) {
            throw new RoleNotFoundException($roleId);
        }

        return $this->roleRepository->deleteById($role);
    }
}
