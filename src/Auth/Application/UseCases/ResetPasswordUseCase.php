<?php

namespace Lauchoit\LaravelHexMod\Auth\Application\UseCases;

use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;

readonly class ResetPasswordUseCase
{
    public function __construct(
        private AuthRepository $authRepository,
    ) {}

    public function execute(string $token, array $data): bool
    {
        return $this->authRepository->resetPassword($token, $data);
    }
}
