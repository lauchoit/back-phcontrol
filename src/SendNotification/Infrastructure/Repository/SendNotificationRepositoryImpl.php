<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\SendNotification;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Factory\EmailMessageBuilder;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Repository\SendNotificationRepository;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Model\SendNotification as SendNotificationModel;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Repository\UserCases\FindAllSendNotificationUseCaseImpl;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Repository\UserCases\SendEmailUseCaseImpl;

class SendNotificationRepositoryImpl extends SendNotificationRepository
{
    public function __construct(
        private readonly SendEmailUseCaseImpl $sendEmail,
        private readonly FindAllSendNotificationUseCaseImpl $findAll,
    ) {}

    /**
     * @throws \Exception
     */
    public function sendNotification(EmailMessageBuilder $message): bool
    {
        return $this->sendEmail->sendNotification($message);
    }

    public function createSendNotification(array $data): bool
    {
        return SendNotificationModel::create($data)->save();
    }

    /**
     * @return array|SendNotification[]
     */
    public function findAll(array $filters = []): array
    {
        return $this->findAll->findAll($filters);
    }
}
