<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Application\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;

readonly class FindByUserIdRoleUseCase
{
    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    /**
     * Finds all Role entities assigned to a user.
     *
     * @param  string  $userId
     * @return Role[]
     */
    public function execute($userId): array
    {
        return $this->roleRepository->findByUserId($userId);
    }
}
