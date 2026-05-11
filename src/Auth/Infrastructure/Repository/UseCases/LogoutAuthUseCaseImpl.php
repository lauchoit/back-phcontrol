<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases;

class LogoutAuthUseCaseImpl
{
    public function execute(): bool
    {
        $auth = auth()->user();
        Token::revokeToken($auth);

        return true;
    }
}
