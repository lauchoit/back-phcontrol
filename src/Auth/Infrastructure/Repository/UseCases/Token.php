<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class Token
{
    public static function generateToken(UserModel $user): string
    {
        return $user->createToken(config('token.token_name'))->accessToken;
    }

    public static function revokeToken(UserModel $user): int
    {
        return $user->tokens()->delete();
    }

    public static function extractTokenFromHeader(): ?string
    {
        $header = request()->header('Authorization');
        if (! $header) {
            return null;
        }

        $parts = explode(' ', $header);
        if (count($parts) !== 2 || strtolower($parts[0]) !== 'bearer') {
            return null;
        }

        return $parts[1];
    }
}
