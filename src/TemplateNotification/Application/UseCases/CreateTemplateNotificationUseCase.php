<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;

readonly class CreateTemplateNotificationUseCase
{
    public function __construct(
        private TemplateNotificationRepository $mailTemplateRepository
    ) {}

    /**
     * Create a new TemplateNotification entity.
     */
    public function execute(array $newMailTemplate): TemplateNotification
    {
        return $this->mailTemplateRepository->create($newMailTemplate);
    }
}
