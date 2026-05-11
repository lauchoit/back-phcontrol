<?php

namespace Lauchoit\LaravelHexMod\Supplier\Application\UseCases;

use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;
use Lauchoit\LaravelHexMod\Supplier\Domain\Repository\SupplierRepository;

readonly class FindAllSupplierUseCase
{
    public function __construct(
        private SupplierRepository $supplierRepository
    ) {}

    /**
     * Finds all Supplier entities.
     *
     * @return Supplier[]
     */
    public function execute(): array
    {
        return $this->supplierRepository->findAll();
    }
}
