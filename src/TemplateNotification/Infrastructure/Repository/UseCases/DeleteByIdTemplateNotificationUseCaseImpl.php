<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;

class DeleteByIdTemplateNotificationUseCaseImpl
{
    /**
     * Deletes a TemplateNotification by its ID.
     */
    public function execute(TemplateNotification $mailTemplate): bool
    {
        return MailTemplateModel::find($mailTemplate->getId())->delete();
    }
}
