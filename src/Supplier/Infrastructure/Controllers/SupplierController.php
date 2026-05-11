<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Lauchoit\LaravelHexMod\Supplier\Application\UseCases\CreateSupplierUseCase;
use Lauchoit\LaravelHexMod\Supplier\Application\UseCases\DeleteByIdSupplierUseCase;
use Lauchoit\LaravelHexMod\Supplier\Application\UseCases\FindAllSupplierUseCase;
use Lauchoit\LaravelHexMod\Supplier\Application\UseCases\FindByIdSupplierUseCase;
use Lauchoit\LaravelHexMod\Supplier\Application\UseCases\UpdateByIdSupplierUseCase;
use Lauchoit\LaravelHexMod\Supplier\Domain\Exceptions\SupplierNotFoundException;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model\Supplier as SupplierModel;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Requests\CreateSupplierRequest;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Requests\UpdateSupplierRequest;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Resources\SupplierResource;

class SupplierController extends Controller
{
    public function __construct(
        private readonly CreateSupplierUseCase $createSupplierUseCase,
        private readonly FindAllSupplierUseCase $findAllSupplierUseCase,
        private readonly FindByIdSupplierUseCase $findByIdSupplierUseCase,
        private readonly DeleteByIdSupplierUseCase $deleteByIdSupplierUseCase,
        private readonly UpdateByIdSupplierUseCase $updateByIdSupplierUseCase,
    ) {}

    public function create(CreateSupplierRequest $supplier): JsonResponse
    {
        $this->authorize('create', SupplierModel::class);

        $newSupplier = $this->createSupplierUseCase->execute($supplier->validated());

        return ApiResponse::success(message: ApiResponse::$SUCCESS_CREATED, data: SupplierResource::make($newSupplier), code: 201);
    }

    public function findAll(): JsonResponse
    {
        $this->authorize('findAll', SupplierModel::class);

        $suppliers = $this->findAllSupplierUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: SupplierResource::collection($suppliers));
    }

    /**
     * Find a Supplier entity by its ID.
     */
    public function findById(string $supplierId): JsonResponse
    {
        $this->authorize('findById', [SupplierModel::class, $supplierId]);

        $supplier = $this->findByIdSupplierUseCase->execute($supplierId);
        if (! $supplier) {
            return ApiResponse::error(message: ApiResponse::$ERROR_NOT_FOUND, code: 404);
        }

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: SupplierResource::make($supplier));
    }

    /**
     * Delete a Supplier entity by its ID.
     */
    public function deleteById(string $supplierId): JsonResponse
    {
        $this->authorize('deleteById', [SupplierModel::class, $supplierId]);

        try {
            $deleted = $this->deleteByIdSupplierUseCase->execute($supplierId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $deleted);
        } catch (SupplierNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Update a Supplier entity by its ID.
     */
    public function updateById(string $supplierId, UpdateSupplierRequest $data): JsonResponse
    {
        $this->authorize('updateById', [SupplierModel::class, $supplierId]);

        try {
            $supplierUpdated = $this->updateByIdSupplierUseCase->execute($supplierId, $data->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: SupplierResource::make($supplierUpdated));
        } catch (SupplierNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }
}
