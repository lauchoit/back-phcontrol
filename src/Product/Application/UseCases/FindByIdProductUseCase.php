<?php

namespace Lauchoit\LaravelHexMod\Product\Application\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;

readonly class FindByIdProductUseCase
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    /**
     * Find by ID Product entities.
     */
    public function execute($productId): ?Product
    {
        return $this->productRepository->findById($productId);
    }
}
