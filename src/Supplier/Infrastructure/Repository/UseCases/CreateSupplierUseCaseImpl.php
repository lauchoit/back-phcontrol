<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Mappers\SupplierMapper;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;

class CreateSupplierUseCaseImpl
{
    public function execute(array $newSupplier): Supplier
    {
        $data = SupplierMapper::toPersistence($newSupplier);
        $dataSource = SupplierModel::create($data);

        return SupplierMapper::toDomain($dataSource->toArray());
    }
}
