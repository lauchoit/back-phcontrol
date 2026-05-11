<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions;

use RuntimeException;

class PermissionNotFoundException extends RuntimeException
{
    public function __construct(int|string $id)
    {
        parent::__construct("Permission with ID {$id} not found.");
    }
}
