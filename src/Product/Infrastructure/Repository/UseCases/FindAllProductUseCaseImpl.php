<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;
use Lauchoit\LaravelHexMod\Product\Domain\Mappers\ProductMapper;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;

class FindAllProductUseCaseImpl
{
    /**
     * @return Product[]
     */
    public function execute(): array
    {
        $productModels = ProductModel::orderBy('order')->paginate(10);

        $items = ProductMapper::toDomainArray($productModels->items());

        $productModels->setCollection(collect($items));

        return $productModels->toArray();
    }
}
