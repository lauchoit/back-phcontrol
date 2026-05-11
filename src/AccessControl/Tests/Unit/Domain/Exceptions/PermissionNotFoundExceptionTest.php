<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Unit\Domain\Exceptions;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\PermissionNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PermissionNotFoundExceptionTest extends TestCase
{
    #[TestDox('Test PermissionNotFoundException message with integer ID')]
    #[Test]
    public function exception_permission_message_with_integer_id(): void
    {
        $id = 123;
        $exception = new PermissionNotFoundException($id);

        $this->assertInstanceOf(PermissionNotFoundException::class, $exception);
        $this->assertSame('Permission with ID 123 not found.', $exception->getMessage());
    }

    #[TestDox('Test PermissionNotFoundException message with string ID')]
    #[Test]
    public function exception_permission_message_with_string_id(): void
    {
        $id = 'abc123';
        $exception = new PermissionNotFoundException($id);

        $this->assertInstanceOf(PermissionNotFoundException::class, $exception);
        $this->assertSame('Permission with ID abc123 not found.', $exception->getMessage());
    }
}
