<?php

namespace Lauchoit\LaravelHexMod\Auth\Domain\Mappers;

use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\User\Domain\Mappers\UserMapper;

class AuthMapper
{
    /**
     * Maps the fields from the AuthModel to the Auth entity.
     *
     * @param  string  $token
     */
    public static function toDomain(array $auth, $token, array $permissions): Auth
    {
        return new Auth(
            user: UserMapper::toDomain($auth),
            token: $token,
            permissions: $permissions,
        );
    }
}
