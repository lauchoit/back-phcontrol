<?php

namespace Lauchoit\LaravelHexMod\Product\Application\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;

readonly class UpdateByIdProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {}

    /**
     * Update a Product entity by its ID.
     */
    public function execute(int $productId, array $data): Product
    {
        return $this->productRepository->updateById($productId, $data);
    }
}
