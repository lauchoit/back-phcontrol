<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource User into an array.
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;
        $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'isActive' => $user->getIsActive(),
            'language' => $user->getLanguage(),
            'createdAt' => $user->getCreatedAt(),
            'updatedAt' => $user->getUpdatedAt(),
        ];

        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null || (is_array($value) && empty($value)))
        );
    }
}
