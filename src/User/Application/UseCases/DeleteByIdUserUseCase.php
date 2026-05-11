<?php

namespace Lauchoit\LaravelHexMod\User\Application\UseCases;

use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;

readonly class DeleteByIdUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private FindByIdUserUseCase $findByIdUserUseCase,
    ) {}

    /**
     * @param  string  $userId
     */
    public function execute($userId): bool
    {
        $user = $this->findByIdUserUseCase->execute($userId);

        return $this->userRepository->deleteById($user);
    }
}
