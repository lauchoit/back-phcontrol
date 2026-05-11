<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\UseCases;

use Illuminate\Support\Facades\Log;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Exceptions\TemplateNotificationNotFoundException;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\TemplateNotificationMapper;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;

class FindByKeyTemplateNotificationUseCaseImpl
{
    /**
     * @param  string  $key
     * @param  string  $language
     */
    public function execute($key, $language): ?TemplateNotification
    {
        $mailTemplateModel = MailTemplateModel::where(['key' => $key, 'locale' => $language])->first();
        if (! $mailTemplateModel) {
            Log::channel('email')->error('TemplateNotification not found', ['key' => $key, 'language' => $language]);
            throw new TemplateNotificationNotFoundException($key, $language);
        }

        return TemplateNotificationMapper::toDomain($mailTemplateModel->toArray());
    }
}
