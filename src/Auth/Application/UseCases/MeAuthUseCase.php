<?php

namespace Lauchoit\LaravelHexMod\Auth\Application\UseCases;

use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;

readonly class MeAuthUseCase
{
    public function __construct(
        private AuthRepository $authRepository
    ) {}

    /**
     * Finds all Auth entities.
     */
    public function execute(): Auth
    {
        return $this->authRepository->me();
    }
}
