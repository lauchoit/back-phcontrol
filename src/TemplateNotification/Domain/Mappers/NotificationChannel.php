<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers;

enum NotificationChannel: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';
    case PUSH = 'push';
    case WEB = 'web';
    case OTHER = 'other';
}
