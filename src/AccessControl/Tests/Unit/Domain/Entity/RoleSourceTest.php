<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\RoleSource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RoleSourceTest extends TestCase
{
    #[TestDox('Verify that RoleSource::FIELDS contains the expected attributes')]
    #[Test]
    public function fields_role_source_contains_expected_attributes(): void
    {
        $expected = ['name', 'guard_name'];
        $this->assertSame(RoleSource::FIELDS, $expected);
    }
}
