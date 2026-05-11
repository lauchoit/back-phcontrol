<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class UpdateByIdUserUseCaseImpl
{
    /**
     * Update a User by its ID.
     *
     * @param  string  $userId
     */
    public function execute($userId, array $data): User
    {
        $userModel = UserModel::find($userId);
        if (! $userModel) {
            throw new UserNotFoundException($userId);
        }
        $userUpdated = UserMapper::toPersistence($data, $userModel->toArray());
        $userModel->fill($userUpdated);
        $userModel->save();

        return UserMapper::toDomain($userModel->toArray());
    }
}
