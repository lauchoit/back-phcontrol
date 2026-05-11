<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Policies;

use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model\Role;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class RolePolicy
{
    // TODO: Implement permissions for Role entity
    /**
     * Determine whether the user can view any models.
     */
    public function findAll(User $user): bool
    {
        return $user->can('roles.find.all');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function findById(User $user): bool
    {
        return $user->can('roles.find.by.id');
    }

    /**
     * Determine whether the user can view roles assigned to a user.
     */
    public function findByUserId(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateById(User $user): bool
    {
        return $user->can('roles.update.by.id');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteById(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function roleSyncPermission(User $user): bool
    {
        return $user->can('roles.sync.permission');
    }
}
