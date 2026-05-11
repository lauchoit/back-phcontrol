<?php

namespace Lauchoit\LaravelHexMod\Supplier\Domain\Repository;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;

abstract class SupplierRepository
{
    /**
     * Creates a new Supplier entity.
     */
    abstract public function create(array $newSupplier): Supplier;

    /**
     * Finds all Supplier entities.
     *
     * @return Supplier[]
     */
    abstract public function findAll(): array;

    /**
     * Finds a Supplier by its ID.
     */
    abstract public function findById(string $supplierId): ?Supplier;

    /**
     * Deletes a Supplier by its ID.
     */
    abstract public function deleteById(Supplier $supplier): bool;

    /**
     * Update a Supplier entity by its ID.
     */
    abstract public function updateById(string $supplierId, array $data): Supplier;
}
