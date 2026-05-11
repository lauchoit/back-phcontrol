<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\Role;

/**
 * @property Role $resource
 */
class RoleResource extends JsonResource
{
    /**
     * Transform the resource Role into an array.
     */
    public function toArray(Request $request): array
    {
        $role = $this->resource;
        $data = [
            'id' => $role->getId(),
            'name' => $role->getName(),
            'guardName' => $role->getGuardName(),
            'usersCount' => $role->getUsersCount(),
            'permissions' => $role->getPermissions(),
            'createdAt' => $role->getCreatedAt(),
            'updatedAt' => $role->getUpdatedAt(),
        ];

        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
        );
    }
}
