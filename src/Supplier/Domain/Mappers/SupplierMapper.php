<?php

namespace Lauchoit\LaravelHexMod\Supplier\Domain\Mappers;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;

class SupplierMapper
{
    /**
     * Maps the fields from the SupplierModel to the Supplier entity.
     */
    public static function toDomain(array $supplier): Supplier
    {
        return new Supplier(
            id: $supplier['id'],
            name: $supplier['name'],
            phone: $supplier['phone'] ?? null,
            createdAt: $supplier['created_at'],
            updatedAt: $supplier['updated_at'],
        );
    }

    /**
     * Converts a array of SupplierModels models to an array of Supplier.
     *
     * @return Supplier[]
     */
    public static function toDomainArray(array $supplierModels): array
    {
        return array_map(fn (array $supplierModel) => self::toDomain($supplierModel), $supplierModels);
    }

    /**
     * Maps raw data to the SupplierModel for persistence.
     */
    public static function toPersistence(array $data, ?array $supplierModel = null): array
    {
        $model = $supplierModel ?? [];

        $model['name'] = $data['name'] ?? $model['name'];
        $model['phone'] = array_key_exists('phone', $data) ? $data['phone'] : ($model['phone'] ?? null);

        return $model;
    }
}
