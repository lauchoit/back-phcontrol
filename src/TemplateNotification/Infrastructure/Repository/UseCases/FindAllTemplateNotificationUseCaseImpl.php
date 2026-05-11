<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\TemplateNotificationMapper;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;

class FindAllTemplateNotificationUseCaseImpl
{
    /**
     * @return TemplateNotification[]
     */
    public function execute(): array
    {
        $mailTemplateModels = MailTemplateModel::all();

        return TemplateNotificationMapper::toDomainArray($mailTemplateModels->toArray());
    }
}
