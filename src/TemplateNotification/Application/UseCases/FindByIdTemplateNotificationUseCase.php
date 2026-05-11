<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;

readonly class FindByIdTemplateNotificationUseCase
{
    public function __construct(
        private readonly TemplateNotificationRepository $mailTemplateRepository
    ) {}

    /**
     * Find by ID TemplateNotification entities.
     *
     * @param  string  $mailTemplateId
     */
    public function execute($mailTemplateId): ?TemplateNotification
    {
        return $this->mailTemplateRepository->findById($mailTemplateId);
    }
}
