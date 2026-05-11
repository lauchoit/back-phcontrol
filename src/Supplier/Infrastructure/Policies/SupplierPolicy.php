<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('supplier.create');
    }

    public function deleteById(User $user, string $supplierId): bool
    {
        return $user->can('supplier.delete.by.id');
    }

    public function findAll(User $user): bool
    {
        return $user->can('supplier.find.all');
    }

    public function findById(User $user, string $supplierId): bool
    {
        return $user->can('supplier.find.by.id');
    }

    public function updateById(User $user, string $supplierId): bool
    {
        return $user->can('supplier.update.by.id');
    }
}
