<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\PermissionMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class FindByIdPermissionUseCaseImpl
{
    /**
     * @param  string  $permissionId
     */
    public function execute($permissionId): ?Permission
    {
        $permissionModel = PermissionModel::find($permissionId);
        if (! $permissionModel) {
            return null;
        }

        return PermissionMapper::toDomain($permissionModel->toArray());
    }
}
