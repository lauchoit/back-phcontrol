<?php

namespace Lauchoit\LaravelHexMod\Supplier\Application\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Repository\SupplierRepository;

readonly class FindByIdSupplierUseCase
{
    public function __construct(
        private readonly SupplierRepository $supplierRepository
    ) {}

    /**
     * Find by ID Supplier entities.
     */
    public function execute(string $supplierId): ?Supplier
    {
        return $this->supplierRepository->findById($supplierId);
    }
}
