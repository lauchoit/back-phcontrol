<?php

namespace Lauchoit\LaravelHexMod\User\Application\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;

readonly class FindByEmailPhoneUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    /**
     * Find by ID User entities.
     *
     * @param  string  $data
     */
    public function execute($data): User
    {
        return $this->userRepository->findByEmailPhone($data);
    }
}
