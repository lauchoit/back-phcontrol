<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\Product\Application\UseCases\CreateProductUseCase;
use Lauchoit\LaravelHexMod\Product\Application\UseCases\DeleteByIdProductUseCase;
use Lauchoit\LaravelHexMod\Product\Application\UseCases\FindAllProductUseCase;
use Lauchoit\LaravelHexMod\Product\Application\UseCases\FindByIdProductUseCase;
use Lauchoit\LaravelHexMod\Product\Application\UseCases\UpdateByIdProductUseCase;
use Lauchoit\LaravelHexMod\Product\Domain\Exceptions\ProductNotFoundException;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Model\Product as ProductModel;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Requests\CreateProductRequest;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Requests\UpdateProductRequest;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Resources\ProductResource;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly CreateProductUseCase $createProductUseCase,
        private readonly FindAllProductUseCase $findAllProductUseCase,
        private readonly FindByIdProductUseCase $findByIdProductUseCase,
        private readonly DeleteByIdProductUseCase $deleteByIdProductUseCase,
        private readonly UpdateByIdProductUseCase $updateByIdProductUseCase,
    ) {}

    public function create(CreateProductRequest $product): JsonResponse
    {
        $this->authorize('create', ProductModel::class);

        $newProduct = $this->createProductUseCase->execute($product->validated());

        return ApiResponse::success(message: ApiResponse::$SUCCESS_CREATED, data: ProductResource::make($newProduct), code: 201);
    }

    public function findAll(): JsonResponse
    {
        $this->authorize('findAll', ProductModel::class);

        $products = $this->findAllProductUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: ProductResource::collection($products));
    }

    /**
     * Find a Product entity by its ID.
     */
    public function findById(string $productId): JsonResponse
    {
        $this->authorize('findById', [ProductModel::class, $productId]);

        $product = $this->findByIdProductUseCase->execute($productId);
        if (! $product) {
            return ApiResponse::error(message: ApiResponse::$ERROR_NOT_FOUND, code: 404);
        }

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: ProductResource::make($product));
    }

    /**
     * Delete a Product entity by its ID.
     */
    public function deleteById(string $productId): JsonResponse
    {
        $this->authorize('deleteById', [ProductModel::class, $productId]);

        try {
            $deleted = $this->deleteByIdProductUseCase->execute($productId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $deleted);
        } catch (ProductNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Update a Product entity by its ID.
     */
    public function updateById(string $productId, UpdateProductRequest $data): JsonResponse
    {
        $this->authorize('updateById', [ProductModel::class, $productId]);

        try {
            $productUpdated = $this->updateByIdProductUseCase->execute($productId, $data->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: ProductResource::make($productUpdated));
        } catch (ProductNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }
}
