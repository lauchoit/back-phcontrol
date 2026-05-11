<?php

namespace Lauchoit\LaravelHexMod\Supplier\Domain\Entity;

class Supplier
{
    private string $id;

    private string $name;

    private ?string $phone;

    private string $createdAt;

    private string $updatedAt;

    public function __construct(string $id, string $name, ?string $phone, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
