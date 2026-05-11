<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class SyncRolesToUserUseCaseImpl
{
    /**
     * Update a User by its ID.
     *
     * @param  string  $userId
     */
    public function execute($userId, array $roleIds): bool
    {
        $userModel = UserModel::find($userId);
        if (! $userModel) {
            throw new UserNotFoundException($userId);
        }

        $userModel->roles()->sync($roleIds);

        return true;
    }
}
