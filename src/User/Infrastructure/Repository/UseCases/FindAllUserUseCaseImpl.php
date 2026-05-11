<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class FindAllUserUseCaseImpl
{
    /**
     * @param  string[]  $filters
     * @return User[]
     */
    public function execute(array $filters): array
    {
        $userModels = UserModel::filters($filters)->get();

        return UserMapper::toDomainArray($userModels->toArray());
    }
}
