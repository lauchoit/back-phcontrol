<?php

namespace Lauchoit\LaravelHexMod\Product\Application\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;

readonly class FindAllProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    /**
     * Finds all Product entities.
     *
     * @return Product[]
     */
    public function execute(): array
    {
        return $this->productRepository->findAll();
    }
}
