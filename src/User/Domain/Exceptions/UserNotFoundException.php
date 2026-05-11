<?php

namespace Lauchoit\LaravelHexMod\User\Domain\Exceptions;

use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use RuntimeException;

class UserNotFoundException extends RuntimeException
{
    public function __construct(int|string $id)
    {
        if ($this->isIdentifier($id)) {
            parent::__construct("User with data {$id} not found.");
        } else {
            parent::__construct(ApiResponse::$USER_NOT_FOUND);
        }
    }

    public function isIdentifier(int|string $id): bool
    {
        if (is_int($id)) {
            return true;
        }

        return ctype_digit($id) || preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id) === 1;
    }
}
