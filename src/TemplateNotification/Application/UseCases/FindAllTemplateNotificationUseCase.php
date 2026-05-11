<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;

readonly class FindAllTemplateNotificationUseCase
{
    public function __construct(
        private TemplateNotificationRepository $mailTemplateRepository
    ) {}

    /**
     * Finds all TemplateNotification entities.
     *
     * @return TemplateNotification[]
     */
    public function execute(): array
    {
        return $this->mailTemplateRepository->findAll();
    }
}
