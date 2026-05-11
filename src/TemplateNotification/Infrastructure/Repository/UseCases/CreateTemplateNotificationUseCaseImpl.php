<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\TemplateNotificationMapper;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;

class CreateTemplateNotificationUseCaseImpl
{
    public function execute(array $newMailTemplate): TemplateNotification
    {
        $data = TemplateNotificationMapper::toPersistence([...$newMailTemplate, 'createdBy' => auth()->id()]);
        $dataSource = MailTemplateModel::create($data);

        return TemplateNotificationMapper::toDomain($dataSource->toArray());
    }
}
