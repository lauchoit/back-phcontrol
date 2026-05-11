<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Repository\SupplierRepository;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases\CreateSupplierUseCaseImpl;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases\DeleteByIdSupplierUseCaseImpl;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases\FindAllSupplierUseCaseImpl;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases\FindByIdSupplierUseCaseImpl;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Repository\UseCases\UpdateByIdSupplierUseCaseImpl;

class SupplierRepositoryImpl extends SupplierRepository
{
    public function __construct(
        private readonly CreateSupplierUseCaseImpl $create,
        private readonly FindAllSupplierUseCaseImpl $findAll,
        private readonly FindByIdSupplierUseCaseImpl $findById,
        private readonly DeleteByIdSupplierUseCaseImpl $deleteById,
        private readonly UpdateByIdSupplierUseCaseImpl $updateById,
    ) {}

    /**
     * Create a new Supplier entity.
     */
    public function create(array $newSupplier): Supplier
    {
        return $this->create->execute($newSupplier);
    }

    /**
     * Finds all Supplier entities.
     *
     * @return Supplier[]
     */
    public function findAll(): array
    {
        return $this->findAll->execute();
    }

    /**
     * Finds a Supplier by its ID.
     */
    public function findById(string $supplierId): ?Supplier
    {
        return $this->findById->execute($supplierId);
    }

    /**
     * Deletes a Supplier by its ID.
     */
    public function deleteById(Supplier $supplier): bool
    {
        return $this->deleteById->execute($supplier);
    }

    /**
     * Update a Supplier entity by its ID.
     */
    public function updateById(string $supplierId, array $data): Supplier
    {
        return $this->updateById->execute($supplierId, $data);
    }
}
