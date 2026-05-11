<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Repository\SendNotificationRepository;

readonly class FindAllSendNotificationUseCase
{
    public function __construct(
        private SendNotificationRepository $sendNotificationRepository,
    ) {}

    public function execute(array $filters = []): array
    {
        return $this->sendNotificationRepository->findAll($filters);
    }
}
