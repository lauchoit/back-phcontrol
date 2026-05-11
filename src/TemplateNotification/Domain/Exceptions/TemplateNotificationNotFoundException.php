<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Domain\Exceptions;

use RuntimeException;

class TemplateNotificationNotFoundException extends RuntimeException
{
    public function __construct(int|string $data, ?string $language = null)
    {
        $message = "TemplateNotification with ID {$data} not found.";
        if ($language) {
            $message = "TemplateNotification with key: {$data} and language: {$language} not found.";
        }

        parent::__construct($message);
    }
}
