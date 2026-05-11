<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Unit\Domain\Exceptions;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Exceptions\RoleNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RoleNotFoundExceptionTest extends TestCase
{
    #[TestDox('Test RoleNotFoundException message with integer ID')]
    #[Test]
    public function exception_role_message_with_integer_id(): void
    {
        $id = 123;
        $exception = new RoleNotFoundException($id);

        $this->assertInstanceOf(RoleNotFoundException::class, $exception);
        $this->assertSame('Role with ID 123 not found.', $exception->getMessage());
    }

    #[TestDox('Test RoleNotFoundException message with string ID')]
    #[Test]
    public function exception_role_message_with_string_id(): void
    {
        $id = 'abc123';
        $exception = new RoleNotFoundException($id);

        $this->assertInstanceOf(RoleNotFoundException::class, $exception);
        $this->assertSame('Role with ID abc123 not found.', $exception->getMessage());
    }
}
