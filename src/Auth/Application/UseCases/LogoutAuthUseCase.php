<?php

namespace Lauchoit\LaravelHexMod\Auth\Application\UseCases;

use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;

readonly class LogoutAuthUseCase
{
    public function __construct(
        private readonly AuthRepository $authRepository
    ) {}

    /**
     * Find by ID Auth entities.
     */
    public function execute(): bool
    {
        return $this->authRepository->logout();
    }
}
