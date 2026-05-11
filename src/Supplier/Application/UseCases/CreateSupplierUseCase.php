<?php

namespace Lauchoit\LaravelHexMod\Supplier\Application\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Repository\SupplierRepository;

readonly class CreateSupplierUseCase
{
    public function __construct(
        private SupplierRepository $supplierRepository
    ) {}

    /**
     * Create a new Supplier entity.
     */
    public function execute(array $newSupplier): Supplier
    {
        return $this->supplierRepository->create($newSupplier);
    }
}
