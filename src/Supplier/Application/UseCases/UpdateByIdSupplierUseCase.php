<?php

namespace Lauchoit\LaravelHexMod\Supplier\Application\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Repository\SupplierRepository;

readonly class UpdateByIdSupplierUseCase
{
    public function __construct(
        private SupplierRepository $supplierRepository,
    ) {}

    /**
     * Update a Supplier entity by its ID.
     */
    public function execute(string $supplierId, array $data): Supplier
    {
        return $this->supplierRepository->updateById($supplierId, $data);
    }
}
