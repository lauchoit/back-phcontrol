<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\PermissionSource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PermissionSourceTest extends TestCase
{
    #[TestDox('Verify that PermissionSource::FIELDS contains the expected attributes')]
    #[Test]
    public function fields_permission_source_contains_expected_attributes(): void
    {
        $expected = ['name', 'guard_name'];
        $this->assertSame($expected, PermissionSource::FIELDS);
    }
}
