<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Exceptions\ProductNotFoundException;
use Lauchoit\LaravelHexMod\Product\Domain\Mappers\ProductMapper;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;

class UpdateByIdProductUseCaseImpl
{
    /**
     * Update a Product by its ID.
     */
    public function execute(string $productId, array $data): Product
    {
        $productModel = ProductModel::find($productId);
        if (! $productModel) {
            throw new ProductNotFoundException($productId);
        }

        $productUpdated = ProductMapper::toPersistence($data, $productModel->toArray());
        $productModel->fill($productUpdated);
        $productModel->save();

        return ProductMapper::toDomain($productModel->toArray());
    }
}
