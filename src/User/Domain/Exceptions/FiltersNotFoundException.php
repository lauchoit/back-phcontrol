<?php

namespace Lauchoit\LaravelHexMod\User\Domain\Exceptions;

use RuntimeException;

class FiltersNotFoundException extends RuntimeException
{
    public function __construct(array $validFilters)
    {
        parent::__construct('error.invalid_filter: '.implode(', ', $validFilters));
    }
}
