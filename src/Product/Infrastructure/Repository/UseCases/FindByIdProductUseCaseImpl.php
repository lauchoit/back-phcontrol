<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Mappers\ProductMapper;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;

class FindByIdProductUseCaseImpl
{
    public function execute(string $productId): ?Product
    {
        $productModel = ProductModel::find($productId);
        if (! $productModel) {
            return null;
        }

        return ProductMapper::toDomain($productModel);
    }
}
