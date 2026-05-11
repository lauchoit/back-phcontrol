<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class DeleteByIdUserUseCaseImpl
{
    /**
     * Deletes a User by its ID.
     */
    public function execute(User $user): bool
    {
        return UserModel::find($user->getId())->delete();
    }
}
