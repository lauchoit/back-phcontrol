<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\TemplateNotificationMapper;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;

class FindByIdTemplateNotificationUseCaseImpl
{
    /**
     * @param  string  $mailTemplateId
     */
    public function execute($mailTemplateId): ?TemplateNotification
    {
        $mailTemplateModel = MailTemplateModel::find($mailTemplateId);
        if (! $mailTemplateModel) {
            return null;
        }

        return TemplateNotificationMapper::toDomain($mailTemplateModel->toArray());
    }
}
