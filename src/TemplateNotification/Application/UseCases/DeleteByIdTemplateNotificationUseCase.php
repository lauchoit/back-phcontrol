<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Exceptions\TemplateNotificationNotFoundException;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;

readonly class DeleteByIdTemplateNotificationUseCase
{
    public function __construct(
        private TemplateNotificationRepository $mailTemplateRepository,
        private FindByIdTemplateNotificationUseCase $findByIdMailTemplateUseCase,
    ) {}

    /**
     * @param  string  $mailTemplateId
     */
    public function execute($mailTemplateId): bool
    {
        $mailTemplate = $this->findByIdMailTemplateUseCase->execute($mailTemplateId);
        if (! $mailTemplate) {
            throw new TemplateNotificationNotFoundException($mailTemplateId);
        }

        return $this->mailTemplateRepository->deleteById($mailTemplate);
    }
}
