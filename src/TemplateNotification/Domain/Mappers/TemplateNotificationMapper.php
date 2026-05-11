<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers;

use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;

class TemplateNotificationMapper
{
    /**
     * Maps the fields from the MailTemplateModel to the TemplateNotification entity.
     */
    public static function toDomain(array $mailTemplate): TemplateNotification
    {
        return new TemplateNotification(
            id: $mailTemplate['id'],
            key: $mailTemplate['key'],
            locale: $mailTemplate['locale'],
            subject: $mailTemplate['subject'],
            bodyHtml: $mailTemplate['body_html'],
            version: $mailTemplate['version'],
            isActive: $mailTemplate['is_active'],
            variables: $mailTemplate['variables'],
            notificationChannel: $mailTemplate['notification_channel'],
            createdAt: $mailTemplate['created_at'],
            updatedAt: $mailTemplate['updated_at'],
        );
    }

    /**
     * Converts a array of MailTemplateModels models to an array of TemplateNotification.
     *
     * @return TemplateNotification[]
     */
    public static function toDomainArray(array $mailTemplateModels): array
    {
        return array_map(fn (array $mailTemplateModel) => self::toDomain($mailTemplateModel), $mailTemplateModels);
    }

    /**
     * Maps raw data to the MailTemplateModel for persistence.
     */
    public static function toPersistence(array $data, ?array $mailTemplateModel = null): array
    {
        $model = $mailTemplateModel ?? [];

        $model['key'] = $data['key'] ?? $model['key'];
        $model['locale'] = $data['locale'] ?? $model['locale'];
        $model['subject'] = $data['subject'] ?? $model['subject'];
        $model['body_html'] = $data['bodyHtml'] ?? $model['body_html'];
        $model['version'] = $data['version'] ?? $model['version'];
        $model['is_active'] = $data['isActive'] ?? $model['is_active'];
        $model['variables'] = $data['variables'] ?? $model['variables'];
        $model['created_by'] = $data['createdBy'] ?? $model['created_by'];
        $model['notification_channel'] = $data['notificationChannel'] ?? $model['notification_channel'];

        return $model;
    }
}
