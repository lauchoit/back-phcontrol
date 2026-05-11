<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\RoleMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role as RoleModel;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class FindAllRoleUseCaseImpl
{
    /**
     * @return Role[]
     */
    public function execute(): array
    {
        $roleModels = RoleModel::query()
            ->leftJoin('model_has_roles', function ($join) {
                $join->on('roles.id', '=', 'model_has_roles.role_id')
                    ->where('model_has_roles.model_type', '=', UserModel::class);
            })
            ->select('roles.*')
            ->selectRaw('COUNT(DISTINCT model_has_roles.model_id) as users_count')
            ->groupBy('roles.id', 'roles.name', 'roles.guard_name', 'roles.created_at', 'roles.updated_at')
            ->get();

        return RoleMapper::toDomainArray($roleModels->toArray());
    }
}
