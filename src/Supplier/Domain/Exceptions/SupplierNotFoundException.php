<?php

namespace Lauchoit\LaravelHexMod\Supplier\Domain\Exceptions;

use RuntimeException;

class SupplierNotFoundException extends RuntimeException
{
    public function __construct(int|string $id)
    {
        parent::__construct("Supplier with ID {$id} not found.");
    }
}
