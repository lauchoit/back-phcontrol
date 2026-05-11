<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;

class DeleteByIdSupplierUseCaseImpl
{
    /**
     * Deletes a Supplier by its ID.
     */
    public function execute(Supplier $supplier): bool
    {
        return SupplierModel::find($supplier->getId())->delete();
    }
}
