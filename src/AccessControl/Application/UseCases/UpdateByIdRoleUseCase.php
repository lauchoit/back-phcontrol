<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;

readonly class UpdateByIdRoleUseCase
{
    public function __construct(
        private RoleRepository $roleRepository,
    ) {}

    /**
     * Update a Role entity by its ID.
     *
     * @param  string  $roleId
     */
    public function execute($roleId, array $data): Role
    {
        return $this->roleRepository->updateById($roleId, $data);
    }
}
