<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\NotificationBuilder;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Mappers\SendNotificationMapper;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Repository\SendNotificationRepository;

readonly class CreateSendNotificationUseCase
{
    public function __construct(
        private SendNotificationRepository $sendNotificationRepository,
    ) {}

    public function execute(NotificationBuilder $data): bool
    {
        $newNotification = SendNotificationMapper::toPersistence($data);

        return $this->sendNotificationRepository->createSendNotification($newNotification);
    }
}
