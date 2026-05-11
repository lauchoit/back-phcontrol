<?php

namespace Lauchoit\LaravelHexMod\Auth\Domain\Repository;

use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;

abstract class AuthRepository
{
    /**
     * Make authenticate user with new Token.
     */
    abstract public function login(array $newAuth): Auth;

    /**
     * Return the authenticated user with new Token.
     */
    abstract public function me(): Auth;

    /**
     * Logout to authenticate user.
     */
    abstract public function logout(): bool;

    abstract public function forgetPassword(string $email): string;

    abstract public function resetPassword(string $token, array $data): bool;
}
