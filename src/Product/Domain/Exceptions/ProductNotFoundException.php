<?php

namespace Lauchoit\LaravelHexMod\Product\Domain\Exceptions;

use RuntimeException;

class ProductNotFoundException extends RuntimeException
{
    public function __construct(int|string $id)
    {
        parent::__construct("Product with ID {$id} not found.");
    }
}
