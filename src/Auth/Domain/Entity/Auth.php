<?php

namespace Lauchoit\LaravelHexMod\Auth\Domain\Entity;

use Lauchoit\LaravelHexMod\User\Domain\Entity\User;

class Auth
{
    private User $user;

    private string $token;

    /**
     * @var array<string>
     */
    private array $permissions;

    public function __construct(User $user, $token, array $permissions)
    {
        $this->user = $user;
        $this->token = $token;
        $this->permissions = $permissions;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
