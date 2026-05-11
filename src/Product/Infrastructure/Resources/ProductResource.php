<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\Product\Domain\Entity\Product;

/**
 * @property Product $resource
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource Product into an array.
     */
    public function toArray(Request $request): array
    {
        $product = $this->resource;
        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'isActive' => $product->getIsActive(),
            'order' => $product->getOrder(),
            'createdAt' => $product->getCreatedAt(),
            'updatedAt' => $product->getUpdatedAt(),
        ];

        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
        );
    }
}
