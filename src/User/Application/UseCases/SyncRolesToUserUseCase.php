<?php

namespace Lauchoit\LaravelHexMod\User\Application\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;

readonly class SyncRolesToUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * Update a User entity by its ID.
     *
     * @param  string  $userId
     */
    public function execute($userId, array $data): bool
    {
        return $this->userRepository->syncRolesToUser($userId, $data);
    }
}
