<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Policies;

use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function findAll(User $user): bool
    {
        return $user->can('permissions.find.all');
    }

    /**
     * Determine whether the user can view their own effective permissions.
     */
    public function findAuthenticatedUserPermissions(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function findById(User $user): bool
    {
        return $user->can('permissions.find.by.id');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('permissions.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateById(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteById(User $user): bool
    {
        return $user->can('permissions.delete.by.id');
    }

    /**
     * Determine whether the user can permanently add the model.
     */
    public function addPermissionToRole(User $user): bool
    {
        return $user->can('permissions.add.role');
    }
}
