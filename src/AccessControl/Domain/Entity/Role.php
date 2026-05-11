<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Entity;

class Role
{
    private string $id;

    private string $name;

    private string $guardName;

    private string $createdAt;

    private string $updatedAt;

    private array $permissions;

    private int $usersCount;

    public function __construct($id, $name, $guardName, $createdAt, $updatedAt, array $permissions = [], int $usersCount = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->guardName = $guardName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->permissions = $permissions;
        $this->usersCount = $usersCount;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getGuardName(): string
    {
        return $this->guardName;
    }

    /**
     * @param  string  $guardName
     */
    public function setGuardName($guardName): void
    {
        $this->guardName = $guardName;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getUsersCount(): int
    {
        return $this->usersCount;
    }
}
