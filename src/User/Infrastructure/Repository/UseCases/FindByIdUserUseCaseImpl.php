<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class FindByIdUserUseCaseImpl
{
    /**
     * @param  string  $userId
     */
    public function execute($userId): ?User
    {
        $userModel = UserModel::find($userId);
        if (! $userModel) {
            throw new UserNotFoundException($userId);
        }

        return UserMapper::toDomain($userModel->toArray());
    }
}
