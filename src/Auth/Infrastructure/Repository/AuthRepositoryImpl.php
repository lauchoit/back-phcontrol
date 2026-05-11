<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\ForgetPasswordUseCaseImpl;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\LoginAuthUseCaseImpl;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\LogoutAuthUseCaseImpl;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\MeAuthUseCaseImpl;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases\ResetPasswordUseCaseImpl;

class AuthRepositoryImpl extends AuthRepository
{
    public function __construct(
        private readonly LoginAuthUseCaseImpl $loginAuthUseCase,
        private readonly MeAuthUseCaseImpl $meAuthUseCase,
        private readonly LogoutAuthUseCaseImpl $logoutAuthUseCase,
        private readonly ForgetPasswordUseCaseImpl $forgetPasswordUseCaseImpl,
        private readonly ResetPasswordUseCaseImpl $resetPasswordUseCaseImpl,
    ) {}

    /**
     * Make authenticate user with new Token.
     */
    public function login(array $newAuth): Auth
    {
        return $this->loginAuthUseCase->execute($newAuth);
    }

    /**
     * Return the authenticated user with new Token.
     */
    public function me(): Auth
    {
        return $this->meAuthUseCase->execute();
    }

    /**
     * Logout to authenticate user.
     */
    public function logout(): bool
    {
        return $this->logoutAuthUseCase->execute();
    }

    /**
     * @param  string  $email
     */
    public function forgetPassword($email): string
    {
        return $this->forgetPasswordUseCaseImpl->execute($email);
    }

    public function resetPassword(string $token, array $data): bool
    {
        return $this->resetPasswordUseCaseImpl->execute($token, $data);
    }
}
