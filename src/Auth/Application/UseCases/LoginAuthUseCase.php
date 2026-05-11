<?php

namespace Lauchoit\LaravelHexMod\Auth\Application\UseCases;

use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;

readonly class LoginAuthUseCase
{
    public function __construct(
        private AuthRepository $authRepository,
    ) {}

    /**
     * Create a new Auth entity.
     */
    public function execute(array $authData): Auth
    {
        return $this->authRepository->login($authData);
    }
}
