<?php

namespace Lauchoit\LaravelHexMod\Product\Application\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;

readonly class CreateProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    /**
     * Create a new Product entity.
     */
    public function execute(array $newProduct): Product
    {
        return $this->productRepository->create($newProduct);
    }
}
