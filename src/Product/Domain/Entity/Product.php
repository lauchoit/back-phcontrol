<?php

namespace Lauchoit\LaravelHexMod\Product\Domain\Entity;

class Product
{
    private string $id;

    private string $name;

    private bool $isActive;

    private int $order;

    private string $createdAt;

    private string $updatedAt;

    public function __construct(string $id, string $name, bool $isActive, int $order, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isActive = $isActive;
        $this->order = $order;
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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): void
    {
        $this->order = $order;
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
