<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Domain\Repository;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\SendNotification;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Factory\EmailMessageBuilder;

abstract class SendNotificationRepository
{
    abstract public function sendNotification(EmailMessageBuilder $message): bool;

    abstract public function createSendNotification(array $data): bool;

    /**
     * @return SendNotification[]
     */
    abstract public function findAll(array $filters = []): array;
}
