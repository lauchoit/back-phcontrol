<?php

namespace Lauchoit\LaravelHexMod\Product\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\ProductSource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ProductSourceTest extends TestCase
{
    #[TestDox('Verify that ProductSource::FIELDS contains the expected attributes')]
    #[Test]
    public function fields_product_source_contains_expected_attributes(): void
    {
        $expected = ['name', 'is_active', 'order'];
        $this->assertSame($expected, ProductSource::FIELDS);
    }
}
