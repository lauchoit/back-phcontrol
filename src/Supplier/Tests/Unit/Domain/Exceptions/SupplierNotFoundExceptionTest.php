<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Unit\Domain\Exceptions;

use Lauchoit\LaravelHexMod\Supplier\Domain\Exceptions\SupplierNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class SupplierNotFoundExceptionTest extends TestCase
{
    #[TestDox('Test SupplierNotFoundException message with integer ID')]
    #[Test]
    public function exception_message_with_integer_id(): void
    {
        $id = 123;
        $exception = new SupplierNotFoundException($id);

        $this->assertInstanceOf(SupplierNotFoundException::class, $exception);
        $this->assertSame('Supplier with ID 123 not found.', $exception->getMessage());
    }

    #[TestDox('Test SupplierNotFoundException message with string ID')]
    #[Test]
    public function exception_message_with_string_id(): void
    {
        $id = 'abc123';
        $exception = new SupplierNotFoundException($id);

        $this->assertInstanceOf(SupplierNotFoundException::class, $exception);
        $this->assertSame('Supplier with ID abc123 not found.', $exception->getMessage());
    }
}
