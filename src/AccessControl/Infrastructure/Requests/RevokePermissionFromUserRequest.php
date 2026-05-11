<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class RevokePermissionFromUserRequest extends FormRequest
{
    use ValidationResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|uuid|exists:users,id',
            'permissionIds.*' => 'required|uuid|exists:permissions,id',
        ];
    }
}
