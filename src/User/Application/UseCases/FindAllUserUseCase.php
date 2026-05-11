<?php

namespace Lauchoit\LaravelHexMod\User\Application\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;

readonly class FindAllUserUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Finds all User entities.
     *
     * @return User[]
     */
    public function execute(array $filters): array
    {
        return $this->userRepository->findAll($filters);
    }
}
