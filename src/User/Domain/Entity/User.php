<?php

namespace Lauchoit\LaravelHexMod\User\Domain\Entity;

class User
{
    /**
     * @var int
     */
    private string $id;

    private string $name;

    private string $lastname;

    private string $email;

    private string $password;

    private string $phone;

    private bool $isActive;

    private string $language;

    private string $createdAt;

    private string $updatedAt;

    public function __construct($id, $name, $lastname, $email, $password, $phone, bool $isActive, $language, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->isActive = $isActive;
        $this->language = $language;
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

    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param  string  $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param  string  $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param  string  $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param  string  $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param  string  $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage($language): void
    {
        $this->language = $language;
    }
}
