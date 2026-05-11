<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;

abstract class TemplateNotificationRepository
{
    /**
     * Creates a new TemplateNotification entity.
     */
    abstract public function create(array $newMailTemplate): TemplateNotification;

    /**
     * Finds all TemplateNotification entities.
     *
     * @return TemplateNotification[]
     */
    abstract public function findAll(): array;

    /**
     * Finds a TemplateNotification by its ID.
     *
     * @param  string  $mailTemplateId
     */
    abstract public function findById($mailTemplateId): ?TemplateNotification;

    /**
     * Deletes a TemplateNotification by its ID.
     */
    abstract public function deleteById(TemplateNotification $mailTemplate): bool;

    /**
     * Update a TemplateNotification entity by its ID.
     *
     * @param  string  $mailTemplateId
     */
    abstract public function updateById($mailTemplateId, array $data): TemplateNotification;

    /**
     * Finds a TemplateNotification by its ID.
     *
     * @param  string  $key
     * @param  string  $language
     */
    abstract public function findByKey($key, $language): TemplateNotification;
}
