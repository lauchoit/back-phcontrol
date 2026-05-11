<?php

namespace Lauchoit\LaravelHexMod\Product\Domain\Mappers;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;

class ProductMapper
{
    /**
     * Maps the fields from the ProductModel to the Product entity.
     */
    public static function toDomain(array $product): Product
    {
        return new Product(
            id: $product['id'],
            name: $product['name'],
            isActive: $product['is_active'],
            order: $product['order'],
            createdAt: $product['created_at'],
            updatedAt: $product['updated_at'],
        );
    }

    /**
     * Converts a array of ProductModels models to an array of Product.
     *
     * @return Product[]
     */
    public static function toDomainArray(array $productModels): array
    {
        return array_map(fn (array $productModel) => self::toDomain($productModel), $productModels);
    }

    /**
     * Maps raw data to the ProductModel for persistence.
     */
    public static function toPersistence(array $data, ?array $productModel = null): array
    {
        $model = $productModel ?? [];

        $model['name'] = $data['name'] ?? $model['name'];
        $model['is_active'] = $data['isActive'] ?? $model['is_active'];
        $model['order'] = $data['order'] ?? $model['order'];

        return $model;
    }
}
