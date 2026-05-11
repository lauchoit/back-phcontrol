<?php

namespace Lauchoit\LaravelHexMod\Auth\Tests\Unit\Domain\Exceptions;

use Lauchoit\LaravelHexMod\Auth\Domain\Exceptions\InvalidCredentialsException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class InvalidCredentialsExceptionTest extends TestCase
{
    #[TestDox('Test InvalidCredentialsException message')]
    #[Test]
    public function exception_message_with_integer_id(): void
    {
        $exception = new InvalidCredentialsException;

        $this->assertSame('Invalid credentials provided.', $exception->getMessage());
    }
}
