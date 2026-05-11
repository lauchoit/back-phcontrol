<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    #[TestDox('Test getters and setters of Permission entity')]
    #[Test]
    public function test_permission_entity_getters_and_setters(): void
    {
        $permission = new Permission(
            '550e8400-e29b-41d4-a716-446655440000',
            'Attribute1',
            'Attribute2',
            '2024-01-01 00:00:00',
            '2024-01-01 00:00:00'
        );

        // Verificar getters iniciales
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $permission->getId());
        $this->assertEquals('Attribute1', $permission->getName());
        $this->assertEquals('Attribute2', $permission->getGuardName());
        $this->assertEquals('2024-01-01 00:00:00', $permission->getCreatedAt());
        $this->assertEquals('2024-01-01 00:00:00', $permission->getUpdatedAt());

        // Probar setters
        $permission->setName('UpdatedAttribute1');
        $permission->setGuardName('UpdatedAttribute2');

        $this->assertEquals('UpdatedAttribute1', $permission->getName());
        $this->assertEquals('UpdatedAttribute2', $permission->getGuardName());
    }
}
