<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity;

class TemplateNotificationSource
{
    public const FIELDS = ['key', 'locale', 'subject', 'body_html', 'version', 'is_active', 'variables', 'notification_channel', 'created_by'];
}
