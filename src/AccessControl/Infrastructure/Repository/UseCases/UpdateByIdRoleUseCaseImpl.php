<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\RoleNotFoundException;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\RoleMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;

class UpdateByIdRoleUseCaseImpl
{
    /**
     * Update a Role by its ID.
     *
     * @param  string  $roleId
     */
    public function execute($roleId, array $data): Role
    {
        $roleModel = RoleModel::find($roleId);
        if (! $roleModel) {
            throw new RoleNotFoundException($roleId);
        }

        $roleUpdated = RoleMapper::toPersistence($data, $roleModel->toArray());
        $roleModel->fill($roleUpdated);
        $roleModel->save();

        return RoleMapper::toDomain($roleModel->toArray());
    }
}
