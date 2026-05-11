<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Unit\Domain\Exceptions;

use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UserNotFoundExceptionTest extends TestCase
{
    #[TestDox('Test UserNotFoundException message with integer ID')]
    #[Test]
    public function exception_message_with_integer_id(): void
    {
        $id = 123;
        $exception = new UserNotFoundException($id);

        $this->assertInstanceOf(UserNotFoundException::class, $exception);
        $this->assertSame('User with data 123 not found.', $exception->getMessage());
    }

    #[TestDox('Test UserNotFoundException message with string ID')]
    #[Test]
    public function exception_message_with_string_id(): void
    {
        $id = 'test@example.com';
        $exception = new UserNotFoundException($id);

        $this->assertInstanceOf(UserNotFoundException::class, $exception);
        $this->assertSame('user.not_found', $exception->getMessage());
    }

    #[TestDox('Test UserNotFoundException message with UUID string ID')]
    #[Test]
    public function exception_message_with_uuid_string_id(): void
    {
        $id = '550e8400-e29b-41d4-a716-446655440000';
        $exception = new UserNotFoundException($id);

        $this->assertInstanceOf(UserNotFoundException::class, $exception);
        $this->assertSame('User with data 550e8400-e29b-41d4-a716-446655440000 not found.', $exception->getMessage());
    }
}
