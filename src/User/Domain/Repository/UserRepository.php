<?php

namespace Lauchoit\LaravelHexMod\User\Domain\Repository;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;

abstract class UserRepository
{
    /**
     * Creates a new User entity.
     */
    abstract public function create(array $newUser): User;

    /**
     * Finds all User entities.
     *
     * @param  string[]  $filters
     * @return User[]
     */
    abstract public function findAll(array $filters): array;

    /**
     * Finds a User by its ID.
     *
     * @param  string  $userId
     */
    abstract public function findById($userId): ?User;

    /**
     * Deletes a User by its ID.
     */
    abstract public function deleteById(User $user): bool;

    /**
     * Update a User entity by its ID.
     *
     * @param  string  $userId
     */
    abstract public function updateById($userId, array $data): User;

    /**
     * Update a User entity by its ID.
     *
     * @param  string  $userId
     */
    abstract public function syncRolesToUser($userId, array $rolesId): bool;

    /**
     * Finds a User by its ID.
     *
     * @param  string  $data
     */
    abstract public function findByEmailPhone($data): ?User;
}
