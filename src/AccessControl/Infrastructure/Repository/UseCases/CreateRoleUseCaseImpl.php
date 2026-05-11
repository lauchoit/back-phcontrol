<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\RoleMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;

class CreateRoleUseCaseImpl
{
    public function execute(array $newRole): Role
    {
        $data = RoleMapper::toPersistence($newRole);
        $dataSource = RoleModel::create($data);

        return RoleMapper::toDomain($dataSource->toArray());
    }
}
