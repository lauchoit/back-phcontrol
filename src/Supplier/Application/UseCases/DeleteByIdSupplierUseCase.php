<?php

namespace Lauchoit\LaravelHexMod\Supplier\Application\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Exceptions\SupplierNotFoundException;
use Lauchoit\LaravelHexMod\Supplier\Domain\Repository\SupplierRepository;

readonly class DeleteByIdSupplierUseCase
{
    public function __construct(
        private SupplierRepository $supplierRepository,
        private FindByIdSupplierUseCase $findByIdSupplierUseCase,
    ) {}

    public function execute(string $supplierId): bool
    {
        $supplier = $this->findByIdSupplierUseCase->execute($supplierId);
        if (! $supplier) {
            throw new SupplierNotFoundException($supplierId);
        }

        return $this->supplierRepository->deleteById($supplier);
    }
}
