<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class DeleteByIdPermissionUseCaseImpl
{
    /**
     * Deletes a Permission by its ID.
     */
    public function execute(Permission $permission): bool
    {
        return PermissionModel::find($permission->getId())->delete();
    }
}
