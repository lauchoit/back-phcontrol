<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\PermissionNotFoundException;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\PermissionMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class UpdateByIdPermissionUseCaseImpl
{
    /**
     * Update a Permission by its ID.
     *
     * @param  string  $permissionId
     */
    public function execute($permissionId, array $data): Permission
    {
        $permissionModel = PermissionModel::find($permissionId);
        if (! $permissionModel) {
            throw new PermissionNotFoundException($permissionId);
        }

        $permissionUpdated = PermissionMapper::toPersistence($data, $permissionModel->toArray());
        $permissionModel->fill($permissionUpdated);
        $permissionModel->save();

        return PermissionMapper::toDomain($permissionModel->toArray());
    }
}
