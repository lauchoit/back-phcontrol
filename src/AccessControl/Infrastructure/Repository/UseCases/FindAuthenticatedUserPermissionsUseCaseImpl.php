<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Mappers\PermissionMapper;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Permission as PermissionModel;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class FindAuthenticatedUserPermissionsUseCaseImpl
{
    /**
     * @param  string  $userId
     * @return Permission[]
     */
    public function execute($userId): array
    {
        $user = UserModel::find($userId);

        if (! $user) {
            throw new UserNotFoundException($userId);
        }

        if ($user->hasRole('super_admin')) {
            $permissions = PermissionModel::query()
                ->where('guard_name', 'api')
                ->orderBy('name')
                ->get();
        } else {
            $permissions = $user->getAllPermissions()
                ->unique('id')
                ->values();
        }

        return PermissionMapper::toDomainArray($permissions->toArray());
    }
}
