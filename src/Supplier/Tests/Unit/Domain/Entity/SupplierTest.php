<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    #[TestDox('Test getters and setters of Supplier entity')]
    #[Test]
    public function test_supplier_entity_getters_and_setters(): void
    {
        $supplier = new Supplier(
            '00000000-0000-0000-0000-000000000001',
            'Name',
            'Phone',
            '2024-01-01 00:00:00',
            '2024-01-01 00:00:00'
        );

        $this->assertEquals('00000000-0000-0000-0000-000000000001', $supplier->getId());
        $this->assertEquals('Name', $supplier->getName());
        $this->assertEquals('Phone', $supplier->getPhone());
        $this->assertEquals('2024-01-01 00:00:00', $supplier->getCreatedAt());
        $this->assertEquals('2024-01-01 00:00:00', $supplier->getUpdatedAt());

        // Probar setters
        $supplier->setName('UpdatedName');
        $supplier->setPhone('UpdatedPhone');
        $supplier->setCreatedAt('2024-02-01 00:00:00');
        $supplier->setUpdatedAt('2024-02-01 00:00:00');

        $this->assertEquals('UpdatedName', $supplier->getName());
        $this->assertEquals('UpdatedPhone', $supplier->getPhone());
        $this->assertEquals('2024-02-01 00:00:00', $supplier->getCreatedAt());
        $this->assertEquals('2024-02-01 00:00:00', $supplier->getUpdatedAt());
    }
}
