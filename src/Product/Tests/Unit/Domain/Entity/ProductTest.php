<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ProductTest extends TestCase
{
    #[TestDox('Test getters and setters of Product entity')]
    #[Test]
    public function test_product_entity_getters_and_setters(): void
    {
        $product = new Product(
            1,
            'Name',
            true,
            123,
            '2024-01-01 00:00:00',
            '2024-01-01 00:00:00'
        );

        // Verificar getters iniciales
        $this->assertEquals(1, $product->getId());
        $this->assertEquals('Name', $product->getName());
        $this->assertEquals(true, $product->getIsActive());
        $this->assertEquals(123, $product->getOrder());
        $this->assertEquals('2024-01-01 00:00:00', $product->getCreatedAt());
        $this->assertEquals('2024-01-01 00:00:00', $product->getUpdatedAt());

        // Probar setters
        $product->setName('UpdatedName');
        $product->setIsActive(false);
        $product->setOrder(999);
        $product->setCreatedAt('2024-02-01 00:00:00');
        $product->setUpdatedAt('2024-02-01 00:00:00');

        $this->assertEquals('UpdatedName', $product->getName());
        $this->assertEquals(false, $product->getIsActive());
        $this->assertEquals(999, $product->getOrder());
        $this->assertEquals('2024-02-01 00:00:00', $product->getCreatedAt());
        $this->assertEquals('2024-02-01 00:00:00', $product->getUpdatedAt());
    }
}
