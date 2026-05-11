<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Repository\UserCases;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\SendNotification;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Mappers\SendNotificationMapper;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Model\SendNotification as SendNotificationModel;

class FindAllSendNotificationUseCaseImpl
{
    /**
     * @return SendNotification[]
     */
    public function findAll(array $filters = []): array
    {
        $notificationsModel = SendNotificationModel::filter($filters)->get();

        return SendNotificationMapper::toDomainArray($notificationsModel->toArray());
    }
}
