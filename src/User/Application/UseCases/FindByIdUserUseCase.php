<?php

namespace Lauchoit\LaravelHexMod\User\Application\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;

readonly class FindByIdUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    /**
     * Find by ID User entities.
     *
     * @param  string  $userId
     */
    public function execute($userId): User
    {
        return $this->userRepository->findById($userId);
    }
}
