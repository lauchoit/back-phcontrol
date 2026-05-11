<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Mappers\SupplierMapper;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;

class FindAllSupplierUseCaseImpl
{
    /**
     * @return Supplier[]
     */
    public function execute(): array
    {
        $supplierModels = SupplierModel::all();

        return SupplierMapper::toDomainArray($supplierModels->toArray());
    }
}
