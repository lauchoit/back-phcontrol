<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Domain\Entity;

abstract class NotificationBuilder
{
    abstract public static function create(): self;

    abstract public function build(): self;
}
