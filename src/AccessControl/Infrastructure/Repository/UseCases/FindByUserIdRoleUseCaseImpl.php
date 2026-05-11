<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\RoleMapper;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class FindByUserIdRoleUseCaseImpl
{
    /**
     * @param  string  $userId
     * @return Role[]
     */
    public function execute($userId): array
    {
        $userModel = UserModel::with([
            'roles' => function ($query) {
                $query->withCount('users')
                    ->with(['permissions' => function ($permissionQuery) {
                        $permissionQuery->select('id', 'name');
                    }]);
            },
        ])->find($userId);

        if (! $userModel) {
            throw new UserNotFoundException($userId);
        }

        return RoleMapper::toDomainArray($userModel->roles->toArray());
    }
}
