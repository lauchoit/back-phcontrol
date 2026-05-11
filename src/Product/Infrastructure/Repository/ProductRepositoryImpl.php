<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases\CreateProductUseCaseImpl;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases\DeleteByIdProductUseCaseImpl;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases\FindAllProductUseCaseImpl;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases\FindByIdProductUseCaseImpl;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases\UpdateByIdProductUseCaseImpl;

class ProductRepositoryImpl extends ProductRepository
{
    public function __construct(
        private readonly CreateProductUseCaseImpl $create,
        private readonly FindAllProductUseCaseImpl $findAll,
        private readonly FindByIdProductUseCaseImpl $findById,
        private readonly DeleteByIdProductUseCaseImpl $deleteById,
        private readonly UpdateByIdProductUseCaseImpl $updateById,
    ) {}

    /**
     * Create a new Product entity.
     */
    public function create(array $newProduct): Product
    {
        return $this->create->execute($newProduct);
    }

    /**
     * Finds all Product entities.
     *
     * @return Product[]
     */
    public function findAll(): array
    {
        return $this->findAll->execute();
    }

    /**
     * Finds a Product by its ID.
     */
    public function findById(int $productId): ?Product
    {
        return $this->findById->execute($productId);
    }

    /**
     * Deletes a Product by its ID.
     */
    public function deleteById(Product $product): bool
    {
        return $this->deleteById->execute($product);
    }

    /**
     * Update a Product entity by its ID.
     */
    public function updateById(int $productId, array $data): Product
    {
        return $this->updateById->execute($productId, $data);
    }
}
