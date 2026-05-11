<?php

namespace Lauchoit\LaravelHexMod\User\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\User\Domain\Entity\UserSource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UserSourceTest extends TestCase
{
    #[TestDox('Verify that UserSource::FIELDS contains the expected attributes')]
    #[Test]
    public function fields_user_source_contains_expected_attributes(): void
    {
        $expected = ['name', 'lastname', 'email', 'phone', 'is_active', 'language'];
        $this->assertSame(UserSource::FIELDS, $expected);
    }
}
