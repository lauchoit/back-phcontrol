<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\PermissionMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class CreatePermissionUseCaseImpl
{
    public function execute(array $newPermission): Permission
    {
        $data = PermissionMapper::toPersistence($newPermission);
        $dataSource = PermissionModel::create($data);

        return PermissionMapper::toDomain($dataSource->toArray());
    }
}
