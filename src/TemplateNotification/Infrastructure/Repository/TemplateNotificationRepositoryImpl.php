<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases\CreateTemplateNotificationUseCaseImpl;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases\DeleteByIdTemplateNotificationUseCaseImpl;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases\FindAllTemplateNotificationUseCaseImpl;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases\FindByIdTemplateNotificationUseCaseImpl;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases\FindByKeyTemplateNotificationUseCaseImpl;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases\UpdateByIdTemplateNotificationUseCaseImpl;

class TemplateNotificationRepositoryImpl extends TemplateNotificationRepository
{
    public function __construct(
        private readonly CreateTemplateNotificationUseCaseImpl $create,
        private readonly FindAllTemplateNotificationUseCaseImpl $findAll,
        private readonly FindByIdTemplateNotificationUseCaseImpl $findById,
        private readonly DeleteByIdTemplateNotificationUseCaseImpl $deleteById,
        private readonly UpdateByIdTemplateNotificationUseCaseImpl $updateById,
        private readonly FindByKeyTemplateNotificationUseCaseImpl $findByKey,
    ) {}

    /**
     * Create a new TemplateNotification entity.
     */
    public function create(array $newMailTemplate): TemplateNotification
    {
        return $this->create->execute($newMailTemplate);
    }

    /**
     * Finds all TemplateNotification entities.
     *
     * @return TemplateNotification[]
     */
    public function findAll(): array
    {
        return $this->findAll->execute();
    }

    /**
     * Finds a TemplateNotification by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function findById($mailTemplateId): ?TemplateNotification
    {
        return $this->findById->execute($mailTemplateId);
    }

    /**
     * Deletes a TemplateNotification by its ID.
     */
    public function deleteById(TemplateNotification $mailTemplate): bool
    {
        return $this->deleteById->execute($mailTemplate);
    }

    /**
     * Update a TemplateNotification entity by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function updateById($mailTemplateId, array $data): TemplateNotification
    {
        return $this->updateById->execute($mailTemplateId, $data);
    }

    /**
     * @param  string  $key
     * @param  string  $language
     */
    public function findByKey($key, $language): TemplateNotification
    {
        return $this->findByKey->execute($key, $language);
    }
}
