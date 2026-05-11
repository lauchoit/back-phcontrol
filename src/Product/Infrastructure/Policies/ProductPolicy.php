<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class ProductPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('product.create');
    }

    public function deleteById(User $user, string $productId): bool
    {
        return $user->can('product.delete.by.id');
    }

    public function findAll(User $user): bool
    {
        return $user->can('product.find.all');
    }

    public function findById(User $user, string $productId): bool
    {
        return $user->can('product.find.by.id');
    }

    public function updateById(User $user, string $productId): bool
    {
        return $user->can('product.update.by.id');
    }
}
