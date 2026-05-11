<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class RoleTest extends TestCase
{
    #[TestDox('Test getters and setters of Role entity')]
    #[Test]
    public function test_role_entity_getters_and_setters(): void
    {
        $role = new Role(
            '550e8400-e29b-41d4-a716-446655440000',
            'Attribute1',
            'Attribute2',
            '2024-01-01 00:00:00',
            '2024-01-01 00:00:00',
            [],
            3
        );

        // Verificar getters iniciales
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $role->getId());
        $this->assertEquals('Attribute1', $role->getName());
        $this->assertEquals('Attribute2', $role->getGuardName());
        $this->assertEquals('2024-01-01 00:00:00', $role->getCreatedAt());
        $this->assertEquals('2024-01-01 00:00:00', $role->getUpdatedAt());
        $this->assertEquals(3, $role->getUsersCount());

        // Probar setters
        $role->setName('UpdatedAttribute1');
        $role->setGuardName('UpdatedAttribute2');

        $this->assertEquals('UpdatedAttribute1', $role->getName());
        $this->assertEquals('UpdatedAttribute2', $role->getGuardName());
    }
}
