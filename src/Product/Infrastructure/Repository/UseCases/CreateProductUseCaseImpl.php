<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Mappers\ProductMapper;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;

class CreateProductUseCaseImpl
{
    public function execute(array $newProduct): Product
    {
        $data = ProductMapper::toPersistence($newProduct);
        $data->save();

        return ProductMapper::toDomain($data);
    }
}
