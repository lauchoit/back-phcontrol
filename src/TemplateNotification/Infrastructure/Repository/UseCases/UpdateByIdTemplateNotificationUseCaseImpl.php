<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Exceptions\TemplateNotificationNotFoundException;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\TemplateNotificationMapper;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;

class UpdateByIdTemplateNotificationUseCaseImpl
{
    /**
     * Update a TemplateNotification by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function execute($mailTemplateId, array $data): TemplateNotification
    {
        $mailTemplateModel = MailTemplateModel::find($mailTemplateId);
        if (! $mailTemplateModel) {
            throw new TemplateNotificationNotFoundException($mailTemplateId);
        }

        $mailTemplateUpdated = TemplateNotificationMapper::toPersistence($data, $mailTemplateModel->toArray());
        $mailTemplateModel->fill($mailTemplateUpdated);
        $mailTemplateModel->save();

        return TemplateNotificationMapper::toDomain($mailTemplateModel->toArray());
    }
}
