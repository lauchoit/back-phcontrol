<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Mappers\SupplierMapper;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;

class FindByIdSupplierUseCaseImpl
{
    public function execute(string $supplierId): ?Supplier
    {
        $supplierModel = SupplierModel::find($supplierId);
        if (! $supplierModel) {
            return null;
        }

        return SupplierMapper::toDomain($supplierModel->toArray());
    }
}
