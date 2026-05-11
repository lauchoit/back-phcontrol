<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;

readonly class UpdateByIdTemplateNotificationUseCase
{
    public function __construct(
        private TemplateNotificationRepository $mailTemplateRepository,
    ) {}

    /**
     * Update a TemplateNotification entity by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function execute($mailTemplateId, array $data): TemplateNotification
    {
        return $this->mailTemplateRepository->updateById($mailTemplateId, $data);
    }
}
