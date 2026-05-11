<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\Supplier;

/**
 * @property Supplier $resource
 */
class SupplierResource extends JsonResource
{
    /**
     * Transform the resource Supplier into an array.
     */
    public function toArray(Request $request): array
    {
        $supplier = $this->resource;
        $data = [
            'id' => $supplier->getId(),
            'name' => $supplier->getName(),
            'phone' => $supplier->getPhone(),
            'createdAt' => $supplier->getCreatedAt(),
            'updatedAt' => $supplier->getUpdatedAt(),
        ];

//        return array_filter(
//            $data,
//            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
//        );
        return $data;
    }
}
