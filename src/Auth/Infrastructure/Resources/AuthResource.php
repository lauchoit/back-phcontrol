<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lauchoit\LaravelHexMod\Auth\Domain\Entity\Auth;
use Lauchoit\LaravelHexMod\User\Infrastructure\Resources\UserResource;

/**
 * @property Auth $resource
 */
class AuthResource extends JsonResource
{
    /**
     * Transform the resource Auth into an array.
     */
    public function toArray(Request $request): array
    {
        $auth = $this->resource;
        $data = [
            'user' => UserResource::make($auth->getUser()),
            'token' => $auth->getToken(),
            'permissions' => array_map(
                fn ($permission) => $permission->getName(),
                $auth->getPermissions()
            ),
        ];

        return array_filter(
            $data,
            fn ($value) => ! ($value === '' || $value === null)
        );
    }
}
