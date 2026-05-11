<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindAuthenticatedUserPermissionsUseCase;
use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;

class MeAuthUseCaseImpl
{
    public function __construct(
        private readonly FindAuthenticatedUserPermissionsUseCase $findAuthenticatedUserPermissionsUseCase,
    ) {}

    public function execute(): Auth
    {
        $user = auth()->user();

        $token = Token::extractTokenFromHeader();
        //        Token::revokeToken($user);
        //        $token = Token::generateToken($user);
        $permissions = $this->findAuthenticatedUserPermissionsUseCase->execute($user->id);

        return new Auth(UserMapper::toDomain($user->toArray()), $token, $permissions);
    }
}
