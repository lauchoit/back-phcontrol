<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;

/**
 * @property TemplateNotification $resource
 */
class TemplateNotificationResource extends JsonResource
{
    /**
     * Transform the resource TemplateNotification into an array.
     */
    public function toArray(Request $request): array
    {
        $mailTemplate = $this->resource;
        $data = [
            'id' => $mailTemplate->getId(),
            'key' => $mailTemplate->getKey(),
            'locale' => $mailTemplate->getLocale(),
            'subject' => $mailTemplate->getSubject(),
            'bodyHtml' => $mailTemplate->getBodyHtml(),
            'version' => $mailTemplate->getVersion(),
            'isActive' => $mailTemplate->getIsActive(),
            'variables' => $mailTemplate->getVariables(),
            'notificationChannel' => $mailTemplate->getNotificationChannel(),
            'createdAt' => $mailTemplate->getCreatedAt(),
            'updatedAt' => $mailTemplate->getUpdatedAt(),
        ];

        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
        );
    }
}
