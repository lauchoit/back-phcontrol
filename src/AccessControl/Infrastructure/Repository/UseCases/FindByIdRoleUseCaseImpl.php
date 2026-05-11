<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\RoleMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;

class FindByIdRoleUseCaseImpl
{
    /**
     * @param  string  $roleId
     */
    public function execute($roleId): ?Role
    {
        $roleModel = RoleModel::with(['permissions' => function ($query) {
            $query->select('id', 'name');
        }])->withCount('users')->whereId($roleId)->first();
        if (! $roleModel) {
            return null;
        }

        return RoleMapper::toDomain($roleModel->toArray());
    }
}
