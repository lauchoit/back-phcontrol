<?php

namespace Lauchoit\LaravelHexMod\Auth\Domain\Exceptions;

use RuntimeException;

class InvalidCredentialsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials provided.');
    }
}
