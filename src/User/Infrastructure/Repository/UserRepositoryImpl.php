<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\CreateUserUseCaseImpl;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\DeleteByIdUserUseCaseImpl;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\FindAllUserUseCaseImpl;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\FindByEmailPhoneUserUseCaseImpl;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\FindByIdUserUseCaseImpl;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\SyncRolesToUserUseCaseImpl;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UseCases\UpdateByIdUserUseCaseImpl;

class UserRepositoryImpl extends UserRepository
{
    public function __construct(
        private readonly CreateUserUseCaseImpl $create,
        private readonly FindAllUserUseCaseImpl $findAll,
        private readonly FindByIdUserUseCaseImpl $findById,
        private readonly DeleteByIdUserUseCaseImpl $deleteById,
        private readonly UpdateByIdUserUseCaseImpl $updateById,
        private readonly SyncRolesToUserUseCaseImpl $syncRolesToUser,
        private readonly FindByEmailPhoneUserUseCaseImpl $findByEmailPhone,
    ) {}

    /**
     * Create a new User entity.
     */
    public function create(array $newUser): User
    {
        return $this->create->execute($newUser);
    }

    /**
     * Finds all User entities.
     *
     * @param  string[]  $filters
     * @return User[]
     */
    public function findAll(array $filters): array
    {
        return $this->findAll->execute(filters: $filters);
    }

    /**
     * Finds a User by its ID.
     *
     * @param  string  $userId
     */
    public function findById($userId): ?User
    {
        return $this->findById->execute($userId);
    }

    /**
     * Deletes a User by its ID.
     */
    public function deleteById(User $user): bool
    {
        return $this->deleteById->execute($user);
    }

    /**
     * Update a User entity by its ID.
     *
     * @param  string  $userId
     */
    public function updateById($userId, array $data): User
    {
        return $this->updateById->execute($userId, $data);
    }

    public function syncRolesToUser($userId, array $rolesId): bool
    {
        return $this->syncRolesToUser->execute($userId, $rolesId);
    }

    public function findByEmailPhone($data): ?User
    {
        return $this->findByEmailPhone->execute($data);
    }
}
