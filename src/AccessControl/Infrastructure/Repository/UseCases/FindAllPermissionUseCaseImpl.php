<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\PermissionMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;

class FindAllPermissionUseCaseImpl
{
    /**
     * @return Permission[]
     */
    public function execute(): array
    {
        $permissionModels = PermissionModel::all();

        return PermissionMapper::toDomainArray($permissionModels->toArray());
    }
}
