<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;

readonly class FindByKeyTemplateNotificationUseCase
{
    public function __construct(
        private readonly TemplateNotificationRepository $mailTemplateRepository
    ) {}

    /**
     * Find by ID TemplateNotification entities.
     *
     * @param  string  $key
     * @param  string  $language
     */
    public function execute($key, $language): TemplateNotification
    {
        return $this->mailTemplateRepository->findByKey($key, $language);
    }
}
