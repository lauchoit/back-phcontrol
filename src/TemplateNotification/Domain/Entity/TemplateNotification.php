<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity;

class TemplateNotification
{
    /**
     * @var int
     */
    private string $id;

    private string $key;

    private string $locale;

    private string $subject;

    private string $bodyHtml;

    private int $version;

    private bool $isActive;

    private string $variables;

    private string $notificationChannel;

    private string $createdAt;

    private string $updatedAt;

    public function __construct($id, $key, $locale, $subject, $bodyHtml,
        int $version,
        bool $isActive, $variables, $notificationChannel, $createdAt, $updatedAt
    ) {
        $this->id = $id;
        $this->key = $key;
        $this->locale = $locale;
        $this->subject = $subject;
        $this->bodyHtml = $bodyHtml;
        $this->version = $version;
        $this->isActive = $isActive;
        $this->variables = $variables;
        $this->notificationChannel = $notificationChannel;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param  string  $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param  string  $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param  string  $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    public function getBodyHtml(): string
    {
        return $this->bodyHtml;
    }

    /**
     * @param  string  $bodyHtml
     */
    public function setBodyHtml($bodyHtml): void
    {
        $this->bodyHtml = $bodyHtml;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getVariables(): string
    {
        return $this->variables;
    }

    /**
     * @param  string  $variables
     */
    public function setVariables($variables): void
    {
        $this->variables = $variables;
    }

    public function getNotificationChannel(): string
    {
        return $this->notificationChannel;
    }

    /**
     * @param  string  $notificationChannel
     */
    public function setNotificationChannel($notificationChannel): void
    {
        $this->notificationChannel = $notificationChannel;
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
