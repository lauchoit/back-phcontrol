<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Domain\Mappers;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\NotificationBuilder;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\SendNotification;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;

readonly class SendNotificationMapper
{
    /**
     * Maps the fields from the UserModel to the User entity.
     */
    public static function toDomain(array $data): SendNotification
    {
        //        dd($data);
        return new SendNotification(
            id: $data['id'],
            to: $data['to'],
            subject: $data['subject'],
            body: $data['body'],
            cc: $data['cc'],
            bcc: $data['bcc'],
            attachments: $data['attachments'],
            replyTo: $data['reply_to'] ?? '',
            channel: $data['channel'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
        );
        //        return SendNotification::create()
        //            ->id($data['id'])
        //            ->to($data['to'])
        //            ->subject($data['subject'])
        //            ->html($data['body'])
        //            ->bcc($data['bcc'])
        //            ->cc($data['cc'])
        //            ->attachments($data['attachments'])
        //            ->replyTo($data['reply_to']);
    }

    /**
     * Converts a array of SendNotificationModels models to an array of SendNotification.
     *
     * @return SendNotification[]
     */
    public static function toDomainArray(array $sendNotificationModels): array
    {
        return array_map(fn (array $sendNotificationModel) => self::toDomain($sendNotificationModel), $sendNotificationModels);
    }

    /**
     * Maps raw data to the UserModel for persistence.
     */
    public static function toPersistence(NotificationBuilder $data, ?array $sendNotificationModel = null): array
    {
        $model = $sendNotificationModel ?? [];

        $model['to'] = $data->getTo() ?? $model['to'];
        $model['subject'] = $data->getSubject() ?? $model['subject'];
        $model['body'] = $data->getHtml() ?? $model['body'];
        $model['cc'] = $data->getCc() ?? $model['cc'];
        $model['bcc'] = $data->getBcc() ?? $model['bcc'];
        $model['attachments'] = $data->getAttachments() ?? $model['attachments'];
        $model['channel'] = 'email';

        if ($data->getReplyTo()) {
            $model['reply_to'] = $data->getReplyTo() ?? $model['reply_to'];
        }

        return $model;
    }
}
