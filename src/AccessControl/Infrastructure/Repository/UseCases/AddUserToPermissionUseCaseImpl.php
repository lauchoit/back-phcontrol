<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class AddUserToPermissionUseCaseImpl
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
            $user->givePermissionTo($permissionIds);

            return true;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error assigning permissions to user: '.$e->getMessage());
        }
    }
}
