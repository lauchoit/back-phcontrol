<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class CreateUserUseCaseImpl
{
    public function execute(array $newUser): User
    {
        $data = UserMapper::toPersistence($newUser);
        $data['password'] = bcrypt($data['password']);
        $dataSource = UserModel::make($data);
        $dataSource->password = $data['password'];
        $dataSource->save();
        $dataSource->refresh();

        return UserMapper::toDomain($dataSource->toArray());
    }
}
