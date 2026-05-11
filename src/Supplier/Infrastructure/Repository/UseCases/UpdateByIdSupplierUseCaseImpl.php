<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Exceptions\SupplierNotFoundException;
use Lauchoit\LaravelHexMod\Supplier\Domain\Mappers\SupplierMapper;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;

class UpdateByIdSupplierUseCaseImpl
{
    /**
     * Update a Supplier by its ID.
     */
    public function execute(string $supplierId, array $data): Supplier
    {
        $supplierModel = SupplierModel::find($supplierId);
        if (! $supplierModel) {
            throw new SupplierNotFoundException($supplierId);
        }

        $supplierUpdated = SupplierMapper::toPersistence($data, $supplierModel->toArray());
        $supplierModel->fill($supplierUpdated);
        $supplierModel->save();

        return SupplierMapper::toDomain($supplierModel->toArray());
    }
}
