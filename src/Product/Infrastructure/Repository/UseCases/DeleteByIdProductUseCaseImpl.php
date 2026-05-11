<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;

class DeleteByIdProductUseCaseImpl
{
    /**
     * Deletes a Product by its ID.
     */
    public function execute(Product $product): bool
    {
        return ProductModel::find($product->getId())->delete();
    }
}
