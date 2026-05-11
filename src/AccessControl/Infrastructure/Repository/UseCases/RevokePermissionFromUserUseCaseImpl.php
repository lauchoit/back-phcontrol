<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;
use Spatie\Permission\PermissionRegistrar;

class RevokePermissionFromUserUseCaseImpl
{
    /**
     * @param  string  $userId
     * @return true
     */
    public function execute($userId, array $permissionIds): bool
    {
        try {
            $user = UserModel::find($userId);
            if (! $user) {
                return false;
            }
            //            $user->revokePermissionTo('user.find.all');
            //
            // //            foreach ($permissionIds as string $permissionId) {
            // //                // valida guard y revoca
            // //                $permission = Permission::find($permissionId);
            // //                $user->revokePermissionTo($permission->name);
            // //            }
            array_map(
                fn ($permissionId) => $user->revokePermissionTo($permissionId), $permissionIds
            );
            app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

            return true;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error revoke permissions from user: '.$e->getMessage());
        }
    }
}
