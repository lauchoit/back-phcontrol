<?php

namespace Lauchoit\LaravelHexMod\Product\Application\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Exceptions\ProductNotFoundException;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;

readonly class DeleteByIdProductUseCase
{
    public function __construct(
        private ProductRepository $productRepository,
        private FindByIdProductUseCase $findByIdProductUseCase,
    ) {}

    public function execute(string $productId): bool
    {
        $product = $this->findByIdProductUseCase->execute($productId);
        if (! $product) {
            throw new ProductNotFoundException($productId);
        }

        return $this->productRepository->deleteById($product);
    }
}
