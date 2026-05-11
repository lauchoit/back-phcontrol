<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;

class AddRoleToPermissionUseCaseImpl
{
    /**
     * @param  string  $roleId
     * @return true
     */
    public function execute($roleId, array $permissionIds): bool
    {
        try {
            $role = Role::find($roleId);
            if (! $role) {
                return false;
            }
            $role->syncPermissions($permissionIds);

            return true;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error assigning permissions to role: '.$e->getMessage());
        }
    }
}
