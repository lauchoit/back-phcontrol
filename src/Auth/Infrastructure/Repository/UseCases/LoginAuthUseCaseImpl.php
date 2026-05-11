<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases;

use Illuminate\Support\Facades\Hash;
use Lauchoit\LaravelHexMod\AccessControl\Application\UseCases\FindAuthenticatedUserPermissionsUseCase;
use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Domain\Exceptions\InvalidCredentialsException;
use Lauchoit\LaravelHexMod\Auth\Domain\Mappers\AuthMapper;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class LoginAuthUseCaseImpl
{
    public function __construct(
        private readonly FindAuthenticatedUserPermissionsUseCase $findAuthenticatedUserPermissionsUseCase,
    ) {}

    public function execute(array $newAuth): Auth
    {
        $user = UserModel::where('email', $newAuth['email'])->first();
        if (! $user || ! Hash::check($newAuth['password'], $user->password)) {
            throw new InvalidCredentialsException;
        }
        auth()->login($user);
        Token::revokeToken($user);
        $token = Token::generateToken($user);
        $permissions = $this->findAuthenticatedUserPermissionsUseCase->execute($user->id);

        return AuthMapper::toDomain($user->toArray(), $token, $permissions);
    }
}
