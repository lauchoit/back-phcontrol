<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Policies;

use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function findAll(User $user): bool
    {
        return $user->can('user.find.all');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function findById(User $user, $search_id): bool
    {
        return $user->can('user.find.by.id') || $user->id === $search_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateById(User $authUser, $targetId): bool
    {
        return $authUser->can('user.update.by.id') || $targetId === $authUser->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteById(User $user): bool
    {
        return $user->can('user.delete.by.id');
    }

    public function syncRolesToUser(User $user): bool
    {
        return $user->can('user.sync.roles');
    }
}
