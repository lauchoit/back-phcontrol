<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class AddRolePermissionRequest extends FormRequest
{
    use ValidationResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roleId' => 'required|uuid|exists:roles,id',
            'permissionIds.*' => 'required|uuid|exists:permissions,id',
        ];
    }
}
