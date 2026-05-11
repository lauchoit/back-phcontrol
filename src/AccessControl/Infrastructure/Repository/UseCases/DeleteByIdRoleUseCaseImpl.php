<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;

class DeleteByIdRoleUseCaseImpl
{
    /**
     * Deletes a Role by its ID.
     */
    public function execute(Role $role): bool
    {
        return RoleModel::find($role->getId())->delete();
    }
}
