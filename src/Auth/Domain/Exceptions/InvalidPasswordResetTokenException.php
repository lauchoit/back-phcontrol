<?php

namespace Lauchoit\LaravelHexMod\Auth\Domain\Exceptions;

use Exception;

class InvalidPasswordResetTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct('password.reset.invalid_token');
    }
}
