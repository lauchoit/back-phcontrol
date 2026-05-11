<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Domain\Entity;

readonly class SendNotificationSource
{
    public const array FIELDS = ['to', 'subject', 'body', 'cc', 'bcc', 'attachments', 'reply_to', 'channel'];
}
