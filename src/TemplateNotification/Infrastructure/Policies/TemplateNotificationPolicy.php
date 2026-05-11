<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User;

class TemplateNotificationPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can('email-template.create');
    }

    public function deleteById(User $user): bool
    {
        return $user->can('email-template.delete.by.id');
    }

    public function findAll(User $user): bool
    {
        return $user->can('email-template.find.all');
    }

    public function findById(User $user): bool
    {
        return $user->can('email-template.find.by.id');
    }

    public function findByKey(User $user): bool
    {
        return $user->can('email-template.find.by.key');
    }

    public function updateById(User $user): bool
    {
        return $user->can('email-template.update.by.id');
    }
}
