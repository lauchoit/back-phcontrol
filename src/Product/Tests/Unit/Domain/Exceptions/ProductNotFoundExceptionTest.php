<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Unit\Domain\Exceptions;

use Lauchoit\LaravelHexMod\Product\Domain\Exceptions\ProductNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ProductNotFoundExceptionTest extends TestCase
{
    #[TestDox('Test ProductNotFoundException message with integer ID')]
    #[Test]
    public function exception_message_with_integer_id(): void
    {
        $id = 123;
        $exception = new ProductNotFoundException($id);

        $this->assertInstanceOf(ProductNotFoundException::class, $exception);
        $this->assertSame('Product with ID 123 not found.', $exception->getMessage());
    }

    #[TestDox('Test ProductNotFoundException message with string ID')]
    #[Test]
    public function exception_message_with_string_id(): void
    {
        $id = 'abc123';
        $exception = new ProductNotFoundException($id);

        $this->assertInstanceOf(ProductNotFoundException::class, $exception);
        $this->assertSame('Product with ID abc123 not found.', $exception->getMessage());
    }
}
