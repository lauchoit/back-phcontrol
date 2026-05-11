<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\SendNotification;

/**
 * @property SendNotification $resource
 */
class SendNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $notification = $this->resource;
        $data = [
            'id' => $notification->getId(),
            'to' => $notification->getTo(),
            'subject' => $notification->getSubject(),
            'body' => $notification->getBody(),
            'cc' => $notification->getCc(),
            'bcc' => $notification->getBcc(),
            'attachments' => $notification->getAttachments(),
            'replyTo' => $notification->getReplyTo(),
            'channel' => $notification->getChannel(),
            'created_at' => $notification->getCreatedAt(),
            'updated_at' => $notification->getUpdatedAt(),
        ];

        //        return $data;
        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
        );
    }
}
