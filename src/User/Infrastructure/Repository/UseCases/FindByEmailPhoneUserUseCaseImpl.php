<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class FindByEmailPhoneUserUseCaseImpl
{
    /**
     * @param  string  $data
     */
    public function execute($data): User
    {
        $userModel = UserModel::where('email', $data)
            ->orWhere('phone', $data)
            ->first();

        if (! $userModel) {
            throw new UserNotFoundException($data);
        }

        return UserMapper::toDomain($userModel->toArray());
    }
}
