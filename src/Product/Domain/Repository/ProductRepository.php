<?php

namespace Lauchoit\LaravelHexMod\Product\Domain\Repository;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;

abstract class ProductRepository
{
    /**
     * Creates a new Product entity.
     */
    abstract public function create(array $newProduct): Product;

    /**
     * Finds all Product entities.
     *
     * @return Product[]
     */
    abstract public function findAll(): array;

    /**
     * Finds a Product by its ID.
     */
    abstract public function findById(int $productId): ?Product;

    /**
     * Deletes a Product by its ID.
     */
    abstract public function deleteById(Product $product): bool;

    /**
     * Update a Product entity by its ID.
     */
    abstract public function updateById(int $productId, array $data): Product;
}
