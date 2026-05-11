<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Entity;

class Permission
{
    /**
     * @var int
     */
    private string $id;

    private string $name;

    private string $guardName;

    private string $createdAt;

    private string $updatedAt;

    public function __construct($id, $name, $guardName, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->guardName = $guardName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}
