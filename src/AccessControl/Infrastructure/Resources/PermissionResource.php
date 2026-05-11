<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Permission;

/**
 * @property Permission $resource
 */
class PermissionResource extends JsonResource
{
    /**
     * Transform the resource Permission into an array.
     */
    public function toArray(Request $request): array
    {
        $permission = $this->resource;
        $data = [
            'id' => $permission->getId(),
            'name' => $permission->getName(),
            'guardName' => $permission->getGuardName(),
            'createdAt' => $permission->getCreatedAt(),
            'updatedAt' => $permission->getUpdatedAt(),
        ];

        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
        );
    }
}
