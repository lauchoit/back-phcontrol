<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions;

use RuntimeException;

class RoleNotFoundException extends RuntimeException
{
    public function __construct(int|string $id)
    {
        parent::__construct("Role with ID {$id} not found.");
    }
}
