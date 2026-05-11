<?php

namespace Lauchoit\LaravelHexMod\Supplier\Tests\Unit\Domain\Entity;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\SupplierSource;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class SupplierSourceTest extends TestCase
{
    #[TestDox('Verify that SupplierSource::FIELDS contains the expected attributes')]
    #[Test]
    public function fields_supplier_source_contains_expected_attributes(): void
    {
        $expected = ['name', 'phone'];
        $this->assertSame($expected, SupplierSource::FIELDS);
    }
}
